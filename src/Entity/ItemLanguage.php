<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\ItemLanguageRepository")]
#[ORM\Table("items_languages")]
class ItemLanguage
{

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: "App\Entity\Item", cascade: ["remove"], inversedBy: "itemsLanguages")]
    #[ORM\JoinColumn(referencedColumnName: "id", onDelete: "CASCADE")]
    private Item $item;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: "App\Entity\Language", cascade: ["remove"], inversedBy: "itemsLanguages")]
    #[ORM\JoinColumn(referencedColumnName: "code", onDelete: "CASCADE")]
    private Language $language;

    #[ORM\Column(type: "string")]
    private string $text;

    /**
     * @return Item
     */
    public function getItem(): Item
    {
        return $this->item;
    }

    /**
     * @param Item $item
     * @return ItemLanguage
     */
    public function setItem(Item $item): ItemLanguage
    {
        $this->item = $item;
        return $this;
    }

    /**
     * @return Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }

    /**
     * @param Language $language
     * @return ItemLanguage
     */
    public function setLanguage(Language $language): ItemLanguage
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return ItemLanguage
     */
    public function setText(string $text): ItemLanguage
    {
        $this->text = $text;
        return $this;
    }



}