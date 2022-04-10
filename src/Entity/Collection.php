<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections as Common;

#[ORM\Entity(repositoryClass: "App\Repository\CollectionRepository")]
#[ORM\Table("collections")]
class Collection
{

    #[ORM\Id]
    #[ORM\Column(type: "string")]
    private string $id;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Collection", inversedBy: "subcollections")]
    #[ORM\JoinColumn(referencedColumnName: "id", onDelete: "CASCADE")]
    private self $parent;

    #[ORM\OneToMany(mappedBy: "parent", targetEntity: "App\Entity\Collection")]
    private Common\Collection $subcollections;

    #[ORM\OneToMany(mappedBy: "collection", targetEntity: "App\Entity\CollectionLanguage")]
    private Common\Collection $collectionsLanguages;

    #[ORM\OneToMany(mappedBy: 'collection', targetEntity: Item::class)]
    private $items;

    public function __construct()
    {
        $this->subcollections = new ArrayCollection();
        $this->collectionsLanguages = new ArrayCollection();
        $this->items = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent ?? null;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Common\Collection
     */
    public function getSubcollections(): Common\Collection
    {
        return $this->subcollections;
    }

    public function addSubcollection(Collection $subcollection): self
    {
        if (!$this->subcollections->contains($subcollection)) {
            $this->subcollections[] = $subcollection;
            $subcollection->setParent($this);
        }

        return $this;
    }

    public function removeSubcollection(Collection $subcollection): self
    {
        if ($this->subcollections->removeElement($subcollection)) {
            // set the owning side to null (unless already changed)
            if ($subcollection->getParent() === $this) {
                $subcollection->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Common\Collection<int, CollectionLanguage>
     */
    public function getCollectionsLanguages(): Common\Collection
    {
        return $this->collectionsLanguages;
    }

    public function getName(string $language): string
    {

        $result = $this->collectionsLanguages->filter(function($colLang) use($language){
            return $colLang->getLanguage()->getCode() === $language;
        });

        return $result->first()->getText() ?? "";
    }

    public function buildUrl(string $language): string
    {
        if (!isset($this->parent)) {
            return urlencode(str_replace(" ","-",strtolower($this->getName($language))));
        }
        $str = $this->parent->buildUrl($language);
        $str.= "/".urlencode(str_replace(" ","-",strtolower($this->getName($language))));

        return $str;
    }

    public function addCollectionsLanguage(CollectionLanguage $collectionsLanguage): self
    {
        if (!$this->collectionsLanguages->contains($collectionsLanguage)) {
            $this->collectionsLanguages[] = $collectionsLanguage;
            $collectionsLanguage->setCollection($this);
        }

        return $this;
    }

    public function removeCollectionsLanguage(CollectionLanguage $collectionsLanguage): self
    {
        if ($this->collectionsLanguages->removeElement($collectionsLanguage)) {
            // set the owning side to null (unless already changed)
            if ($collectionsLanguage->getCollection() === $this) {
                $collectionsLanguage->setCollection(null);
            }
        }

        return $this;
    }

    /**
     * @return Common\Collection<int, Item>
     */
    public function getItems(): Common\Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setCollection($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getCollection() === $this) {
                $item->setCollection(null);
            }
        }

        return $this;
    }

}