<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\LanguageRepository")]
#[ORM\Table("languages")]
class Language
{

    #[ORM\Id]
    #[ORM\Column(name: "code", type: "string")]
    private string $code;

    #[ORM\Column(type: "string")]
    private string $flag;

    #[ORM\Column(type: "string",unique: true)]
    private string $name;

    #[ORM\OneToMany(mappedBy: "language", targetEntity: "App\Entity\CollectionLanguage")]
    private Collection $collectionsLanguages;

    #[ORM\OneToMany(mappedBy: "language", targetEntity: "App\Entity\ItemLanguage")]
    private Collection $itemsLanguages;

    public function __construct(string $code, string $flag, string $name)
    {
        $this->collectionsLanguages = new ArrayCollection();
        $this->itemsLanguages = new ArrayCollection();
        $this->code = $code;
        $this->flag = $flag;
        $this->name = $name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getFlag(): ?string
    {
        return $this->flag;
    }

    public function setFlag(string $flag): self
    {
        $this->flag = $flag;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, CollectionLanguage>
     */
    public function getCollectionsLanguages(): Collection
    {
        return $this->collectionsLanguages;
    }

    public function addCollectionsLanguage(CollectionLanguage $collectionsLanguage): self
    {
        if (!$this->collectionsLanguages->contains($collectionsLanguage)) {
            $this->collectionsLanguages[] = $collectionsLanguage;
            $collectionsLanguage->setLanguage($this);
        }

        return $this;
    }

    public function removeCollectionsLanguage(CollectionLanguage $collectionsLanguage): self
    {
        if ($this->collectionsLanguages->removeElement($collectionsLanguage)) {
            // set the owning side to null (unless already changed)
            if ($collectionsLanguage->getLanguage() === $this) {
                $collectionsLanguage->setLanguage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ItemLanguage>
     */
    public function getItemsLanguages(): Collection
    {
        return $this->itemsLanguages;
    }

    public function addItemsLanguage(ItemLanguage $itemsLanguage): self
    {
        if (!$this->itemsLanguages->contains($itemsLanguage)) {
            $this->itemsLanguages[] = $itemsLanguage;
            $itemsLanguage->setLanguage($this);
        }

        return $this;
    }

    public function removeItemsLanguage(ItemLanguage $itemsLanguage): self
    {
        if ($this->itemsLanguages->removeElement($itemsLanguage)) {
            // set the owning side to null (unless already changed)
            if ($itemsLanguage->getLanguage() === $this) {
                $itemsLanguage->setLanguage(null);
            }
        }

        return $this;
    }

}