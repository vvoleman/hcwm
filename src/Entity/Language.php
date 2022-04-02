<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
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
    private ArrayCollection $collectionsLanguages;

    public function __construct()
    {
    }

    public function getCode(): string
    {
        return $this->code;
    }

}