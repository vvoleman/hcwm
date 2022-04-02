<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table("collections_languages")]
class CollectionLanguage
{

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: "App\Entity\Collection", cascade: ["remove"], inversedBy: "collectionsLanguages")]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private Collection $collection;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: "App\Entity\Language", cascade: ["remove"], inversedBy: "collectionsLanguages")]
    #[ORM\JoinColumn(referencedColumnName: "code", onDelete: "CASCADE")]
    private Language $language;

    #[ORM\Column(type: "string")]
    private string $text;

    public function getCollection(): Collection
    {
        return $this->collection;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function getText(): string
    {
        return $this->text;
    }
}