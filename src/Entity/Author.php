<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\AuthorRepository")]
#[ORM\Table("authors")]
class Author
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type:"string")]
    private string $firstName;

    #[ORM\Column(type:"string")]
    private string $lastName;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Item", cascade: ["remove"], inversedBy: "authors")]
    #[ORM\JoinColumn(referencedColumnName: "id",onDelete: "CASCADE")]
    private Item $item;

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getItem(): Item
    {
        return $this->item;
    }

    public function setItem(Item $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }



}