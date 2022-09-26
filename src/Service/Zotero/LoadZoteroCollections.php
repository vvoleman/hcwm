<?php

namespace App\Service\Zotero;

use App\DataContainers\Zotero\User;
use App\Entity\Collection;
use App\Entity\CollectionLanguage;
use App\Exception\BadLanguageFormatException;
use App\Exception\LanguageNotFoundException;
use App\Repository\CollectionLanguageRepository;
use App\Repository\CollectionRepository;
use App\Repository\LanguageRepository;
use Doctrine\ORM\EntityManagerInterface;
use ZoteroApi\Endpoint\AbstractEndpoint;
use ZoteroApi\Endpoint\Collections;
use ZoteroApi\Exceptions\ZoteroAccessDeniedException;
use ZoteroApi\Exceptions\ZoteroBadRequestException;
use ZoteroApi\Exceptions\ZoteroConnectionException;
use ZoteroApi\Exceptions\ZoteroEndpointNotFoundException;
use ZoteroApi\Exceptions\ZoteroInvalidChainingException;
use ZoteroApi\Source\AbstractSource;
use ZoteroApi\Source\KeysSource;
use ZoteroApi\ZoteroApi;

class LoadZoteroCollections
{
    private PrepareLanguages $prepareLanguages;
    private EntityManagerInterface $entityManager;
    private CollectionRepository $collectionRepository;
    private CollectionLanguageRepository $collectionLanguageRepository;

    private array $usedCollectionsIds;

    public function __construct(
        CollectionRepository $collectionRepository,
        CollectionLanguageRepository $collectionLanguageRepository,
        EntityManagerInterface $entityManager,
        PrepareLanguages $prepareLanguages
    ) {
        $this->prepareLanguages = $prepareLanguages;
        $this->entityManager = $entityManager;
        $this->collectionRepository = $collectionRepository;
        $this->collectionLanguageRepository = $collectionLanguageRepository;
    }

    /**
     * @throws ZoteroConnectionException
     * @throws ZoteroInvalidChainingException
     * @throws ZoteroAccessDeniedException
     * @throws ZoteroBadRequestException
     * @throws ZoteroEndpointNotFoundException
     */
    public function load(string $apiKey, AbstractSource $source, string $id = AbstractEndpoint::TOP)
    {
        $array = $this->getCollectionsFromApi($apiKey, $source, $id);
		dd($array);
        $this->makeCollectionsEntities($array);
        $this->removeMissingCollections();

        $this->entityManager->flush();
    }

    private function makeCollectionsEntities(array $collections, Collection $parent = null)
    {
        foreach ($collections as $col) {
            $lang = $this->prepareLanguages->prepare($col["data"]["name"]);
            $this->usedCollectionsIds[] = $col["key"];
            try{
                $collection = $this->collectionRepository->getOrCreate($col["key"]);
            }catch (\Exception $e){
                dd($lang);
            }
            $this->entityManager->persist($collection);

            if(!!$parent){
                $collection->setParent($parent);
            }


            foreach ($lang as $item) {
                $colLang = $this->collectionLanguageRepository->getOrCreate($collection,$item["language"])
                    ->setText($item["text"]);

                $this->entityManager->persist($colLang);
            }

            if(sizeof($col["subcollections"] ?? []) > 0){
                $this->makeCollectionsEntities($col["subcollections"],$collection);
            }
        }
    }

    /**
     * Gets Collections from Zotero API and returns key-value array
     *
     * @param string $apiKey
     * @param AbstractSource $source
     * @param string $id
     * @return array
     * @throws ZoteroAccessDeniedException
     * @throws ZoteroBadRequestException
     * @throws ZoteroConnectionException
     * @throws ZoteroEndpointNotFoundException
     * @throws ZoteroInvalidChainingException
     */
    private function getCollectionsFromApi(string $apiKey, AbstractSource $source, string $id = AbstractEndpoint::TOP): array
    {
        $api = new ZoteroApi($apiKey, $source);
        $api->setEndpoint(
            (new Collections($id))
        );
        $api->run();
        $cols = $api->getBody();
		dd($cols);
        for ($i = 0; $i < sizeof($cols); $i++) {
            $sub = $this->loadSubCollectionsFromApi($api, $cols[$i]["key"]);
            if (isset($sub) && sizeof($sub) > 0) {
                $cols[$i]["subcollections"] = $sub;
            }
        }
        return $cols;
    }

    /**
     * Loads subcollection from API
     *
     * @throws ZoteroConnectionException
     * @throws ZoteroInvalidChainingException
     * @throws ZoteroBadRequestException
     * @throws ZoteroAccessDeniedException
     * @throws ZoteroEndpointNotFoundException
     */
    private function loadSubCollectionsFromApi(ZoteroApi $api, string $id)
    {
        $api->setEndpoint(
            (new Collections($id))
                ->setEndpoint(new Collections(AbstractEndpoint::ALL))
        );
        $api->run();
        $cols = $api->getBody();
        for ($i = 0; $i < sizeof($cols); $i++) {
            $subs = $this->loadSubCollectionsFromApi($api, $cols[$i]["key"]);
            if (isset($subs) && sizeof($subs) > 0) {
                $cols[$i]["subcollections"] = $subs;
            }
        }
        return $cols;
    }

    private function removeMissingCollections(){
        $missingCollections = $this->collectionRepository->getWhereIdNotIn($this->usedCollectionsIds);
        foreach ($missingCollections as $missingCollection) {
            $this->collectionRepository->remove($missingCollection,false);
        }
    }

    /**
     * Returns User DataContainer for a API key
     *
     * @param string $apiKey
     * @return ?User
     * @throws ZoteroAccessDeniedException
     * @throws ZoteroBadRequestException
     * @throws ZoteroConnectionException
     * @throws ZoteroEndpointNotFoundException
     */
    public function getUserByAPI(string $apiKey): ?User
    {
        $api = new ZoteroApi($apiKey, new KeysSource($apiKey));
        $api->run();
        return User::createFromBody($api->getBody());
    }

}