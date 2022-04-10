<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\ItemRepository")]
#[ORM\Table("items")]
class Item
{

    #[ORM\Id]
    #[ORM\Column(type: "string")]
    private string $id;

    #[ORM\Column(type: "string")]
    private string $url;

    #[ORM\Column(type: "datetime",nullable: true)]
    private ?\DateTime $date;

    #[ORM\OneToMany(mappedBy: "items", targetEntity: "App\Entity\Author",cascade: ["persist"])]
    private \Doctrine\Common\Collections\Collection $authors;

    #[ORM\OneToMany(mappedBy: "item", targetEntity: "App\Entity\ItemLanguage")]
    private \Doctrine\Common\Collections\Collection $itemsLanguages;

    #[ORM\ManyToMany(targetEntity: "App\Entity\Tag",inversedBy: "items")]
    private \Doctrine\Common\Collections\Collection $tags;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Collection", inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false,onDelete: "CASCADE")]
    private ?Collection $collection;

    public function __construct() {
        $this->authors = new ArrayCollection();
        $this->itemsLanguages = new ArrayCollection();
    }

    public function getAuthors(): ArrayCollection
    {
        return $this->authors;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setTags(array $tags): self{
        $this->tags = new ArrayCollection($tags);
        return $this;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): Item
    {
        $this->url = $url;
        return $this;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): Item
    {
        $this->date = $date;
        return $this;
    }

    public function setAuthors(array $authors): self
    {
        $this->authors = new ArrayCollection($authors);

        return $this;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
            $author->setItem($this);
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        if ($this->authors->removeElement($author)) {
            // set the owning side to null (unless already changed)
            if ($author->getItem() === $this) {
                $author->setItem(null);
            }
        }

        return $this;
    }


    public function getCollection(): ?Collection
    {
        return $this->collection;
    }

    public function setCollection(?Collection $collection): self
    {
        $this->collection = $collection;

        return $this;
    }


}