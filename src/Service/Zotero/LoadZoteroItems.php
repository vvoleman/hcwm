<?php

namespace App\Service\Zotero;

use App\Entity\Author;
use App\Entity\Collection;
use App\Entity\Item;
use App\Entity\Tag;
use App\Repository\CollectionRepository;
use App\Repository\ItemLanguageRepository;
use App\Repository\ItemRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use ZoteroApi\Endpoint\AbstractEndpoint;
use ZoteroApi\Endpoint\Collections;
use ZoteroApi\Endpoint\Items;
use ZoteroApi\Exceptions\ZoteroAccessDeniedException;
use ZoteroApi\Exceptions\ZoteroBadRequestException;
use ZoteroApi\Exceptions\ZoteroConnectionException;
use ZoteroApi\Exceptions\ZoteroEndpointNotFoundException;
use ZoteroApi\Exceptions\ZoteroInvalidChainingException;
use ZoteroApi\ZoteroApi;

class LoadZoteroItems
{

    /** @var CollectionRepository */
    private CollectionRepository $collectionRepository;

    /** @var ItemRepository */
    private ItemRepository $itemRepository;
    private EntityManagerInterface $entityManager;
    private PrepareLanguages $prepareLanguages;
    private ItemLanguageRepository $itemLanguageRepository;
    private TagRepository $tagRepository;

    /** @var array<string,Tag>  */
    private array $tags = [];

    public function __construct(
        CollectionRepository $collectionRepository,
        ItemLanguageRepository $itemLanguageRepository,
        TagRepository $tagRepository,
        ItemRepository $itemRepository,
        EntityManagerInterface $entityManager,
        PrepareLanguages $prepareLanguages,
    ) {
        $this->collectionRepository = $collectionRepository;
        $this->itemRepository = $itemRepository;
        $this->entityManager = $entityManager;
        $this->prepareLanguages = $prepareLanguages;
        $this->itemLanguageRepository = $itemLanguageRepository;
        $this->tagRepository = $tagRepository;
    }

    /**
     * @throws \App\Exception\LanguageNotFoundException
     * @throws ZoteroEndpointNotFoundException
     * @throws ZoteroAccessDeniedException
     * @throws ZoteroBadRequestException
     * @throws ZoteroConnectionException
     * @throws ZoteroInvalidChainingException
     * @throws \App\Exception\BadLanguageFormatException
     */
    public function loadAllItems(ZoteroApi $api)
    {

        $collections = $this->collectionRepository->findAll();

        foreach ($collections as $collection) {
            $this->loadItemsForCollection($api,$collection);
        }
    }

    /**
     * @throws \App\Exception\LanguageNotFoundException
     * @throws ZoteroEndpointNotFoundException
     * @throws ZoteroBadRequestException
     * @throws ZoteroAccessDeniedException
     * @throws ZoteroConnectionException
     * @throws ZoteroInvalidChainingException
     * @throws \App\Exception\BadLanguageFormatException
     */
    public function loadForCollection(ZoteroApi $api, Collection $collection){
        $this->loadItemsForCollection($api,$collection);
    }

    /**
     * @param ZoteroApi $api
     * @param Collection $collection
     * @return void
     * @throws ZoteroAccessDeniedException
     * @throws ZoteroBadRequestException
     * @throws ZoteroConnectionException
     * @throws ZoteroEndpointNotFoundException
     * @throws ZoteroInvalidChainingException
     * @throws \App\Exception\BadLanguageFormatException
     * @throws \App\Exception\LanguageNotFoundException
     */
    private function loadItemsForCollection(ZoteroApi $api, Collection $collection): void
    {
        $api->setEndpoint(
            (new Collections($collection->getId()))
                ->setEndpoint(new Items(AbstractEndpoint::ALL))
        );
        $api->run();
        $data = $api->getBody();

        foreach ($data as $datum) {
            if($datum["data"]["itemType"] == "note") continue;

            $item = $this->itemRepository->getOrCreate($datum["key"]);

            $item->setUrl($datum["data"]["url"] ?? "");

            // Get languages for title
            $languages = $this->prepareLanguages->prepare($datum["data"]["title"]);

            // Add languages for item
            foreach ($languages as $lang) {
                $itemLang = $this->itemLanguageRepository->getOrCreate($item, $lang["language"])
                    ->setText($lang["text"]);
                $this->entityManager->persist($itemLang);
            }

            // Set collection to item
            $item->setCollection($collection);

            // Loads tags
            $this->getTags($datum["data"]["tags"],$item);

            // Load authors
            $this->getAuthors($datum["data"]["creators"],$item);
        }
        $this->entityManager->flush();
    }

    private function getTags(array $tags, Item $item): void
    {
        foreach ($tags as $tagItem) {
            if(!array_key_exists($tagItem["tag"],$this->tags)){
                $tag = $this->tagRepository->getOrCreate($tagItem["tag"]);
                $this->tags[$tag->getText()] = $tag;
            }else{
                $tag = $this->tags[$tagItem["tag"]];
            }
            $tag->addItem($item);

        }
    }

    private function getAuthors(array $authors, Item $item): void
    {
        foreach ($authors as $authorItem) {
            if($authorItem["creatorType"] !== "author") continue;

            $author = (new Author())
                ->setItem($item)
                ->setFirstName($authorItem["firstName"])
                ->setLastName($authorItem["lastName"]);

            $this->entityManager->persist($author);

            $result[] = $author;
        }
    }

}