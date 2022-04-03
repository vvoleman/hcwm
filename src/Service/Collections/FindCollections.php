<?php

namespace App\Service\Collections;

use App\Entity\Collection;
use App\Exception\CollectionNotFoundException;
use App\Repository\CollectionLanguageRepository;
use App\Repository\CollectionRepository;

class FindCollections
{

    /** @var CollectionRepository */
    private CollectionRepository $collectionRepository;

    /** @var CollectionLanguageRepository */
    private CollectionLanguageRepository $languageRepository;

    public function __construct(
        CollectionRepository $collectionRepository,
        CollectionLanguageRepository $languageRepository
    ) {
        $this->collectionRepository = $collectionRepository;
        $this->languageRepository = $languageRepository;
    }

    /**
     * @param string $string
     * @return array
     * @throws CollectionNotFoundException
     */
    public function find(string $string): array
    {
        $string = urldecode(str_replace("-"," ",$string));
        if ($string == "") {
            return [
                "parent" => null,
                "children" => $this->collectionRepository->findBy([
                    "parent"=>null
                ])
            ];
        }

        $colLang = $this->languageRepository->findOneBy([
            "text" => $string
        ]);

        $collection = $colLang?->getCollection();

        if (!$collection) {
            throw new CollectionNotFoundException(sprintf("Unable to find collection '%s'", $string));
        }

        return [
            "parent" => $collection,
            "children" => $collection->getSubcollections()
        ];
    }

}