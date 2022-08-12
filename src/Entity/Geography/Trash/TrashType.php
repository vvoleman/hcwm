<?php

namespace App\Entity\Geography\Trash;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\Geography\Trash\TrashTypeRepository")]
#[ORM\Table("trash_types")]
class TrashType
{
	#[ORM\Id]
	#[ORM\Column(type: "string")]
	private string $id;

	#[ORM\Column(type: 'string')]
	private string $description;

	public function __construct(string $id) { $this->id = $id; }

	public function getId(): ?string
	{
		return $this->id;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription(string $description): self
	{
		$this->description = $description;

		return $this;
	}

}