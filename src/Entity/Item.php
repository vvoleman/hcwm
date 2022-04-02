<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table("items")]
class Item
{

    #[ORM\Id]
    #[ORM\Column(type: "string")]
    private string $id;

    #[ORM\OneToMany(mappedBy: "items", targetEntity: "App\Entity\Author",cascade: ["persist"])]
    private ArrayCollection $authors;

    public function __construct() {
        $this->authors = new ArrayCollection();
    }

    public function getAuthors(): ArrayCollection
    {
        return $this->authors;
    }


}