<?php

namespace App\Service\Collections;

use App\Entity\Collection;
use App\Exception\CollectionNotFoundException;
use App\Repository\CollectionLanguageRepository;
use App\Repository\CollectionRepository;
use App\Service\Util\NormalizeChars;

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
     * Returns array with keys 'parent' and 'child'. Parent is Collection, child is Collection[]
     *
     * @param string $string
     * @return array<string,mixed>
     * @throws CollectionNotFoundException
     */
    public function find(string $string): array
    {
        $string = NormalizeChars::normalize(str_replace("-"," ",$string));

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