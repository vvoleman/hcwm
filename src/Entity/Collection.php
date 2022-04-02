<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table("collections")]
class Collection
{

    #[ORM\Id]
    #[ORM\Column(type: "string")]
    private string $id;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Collection", inversedBy: "subcollections")]
    #[ORM\JoinColumn(referencedColumnName: "id",onDelete: "CASCADE")]
    private self $parent;

    #[ORM\OneToMany(mappedBy: "parent", targetEntity: "App\Entity\Collection")]
    private ArrayCollection $subcollections;

    #[ORM\OneToMany(mappedBy: "collection", targetEntity: "App\Entity\CollectionLanguage")]
    private ArrayCollection $collectionsLanguages;

    public function __construct(string $id)
    {
        $this->id = $id;
        $this->subcollections = new ArrayCollection();
    }

    public function setParent(Collection $collection){
        $this->parent = $collection;
    }

    public function getParent(): Collection
    {
        return $this->parent;
    }

    public function addSubcollection(Collection $collection)
    {
        $this->subcollections->add($collection);
    }

    public function getLanguages(): ArrayCollection
    {
        return $this->collectionsLanguages->map(function ($collectionLanguage){
            /** @var CollectionLanguage $collectionLanguage */
            return $collectionLanguage->getLanguage();
        });
    }

    public function getSpecificLanguage(string $code): ?Language{
        $data = $this->collectionsLanguages->filter(function ($collectionLanguage) use($code){
            /** @var CollectionLanguage $collectionLanguage */
            return $collectionLanguage->getLanguage()->getCode() == $code;
        });

        return !$data->isEmpty() ? $data->get(0)->getLanguage() : null;
    }

}