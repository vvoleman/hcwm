<?php

namespace App\Service\Zotero\Updated\Entity;

use Doctrine\ORM\EntityManagerInterface;

class Author implements IDoctrineEntity
{

	private string $firstName;
	private string $lastName;

	function __construct(string $firstName, string $lastName)
	{
		$this->firstName = $firstName;
		$this->lastName = $lastName;
	}

	public function getFirstName(): string
	{
		return $this->firstName;
	}

	public function getLastName(): string
	{
		return $this->lastName;
	}

	public function makeDoctrineEntity(EntityManagerInterface $manager): \App\Entity\Author
	{
		$author = new \App\Entity\Author();
		$author->setFirstName($this->firstName);
		$author->setLastName($this->lastName);

		$manager->persist($author);

		return $author;
	}

}