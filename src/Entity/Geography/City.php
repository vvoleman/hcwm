<?php

namespace App\Entity\Geography;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\Geography\CityRepository")]
#[ORM\Table("cities")]
class City
{

	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column(type: "string")]
	private string $id;

	#[ORM\Column(type: "string")]
	private string $name;

	#[ORM\Column(type: 'float')]
	private float $longitude;

	#[ORM\Column(type: 'float')]
	private float $latitude;

	public function __construct(string $id) {
		$this->id = $id;
	}

	public function getId(): ?string
	{
		return $this->id;
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

	public function getLongitude(): ?float
	{
		return $this->longitude;
	}

	public function setLongitude(float $longitude): self
	{
		$this->longitude = $longitude;

		return $this;
	}

	public function getLatitude(): ?float
	{
		return $this->latitude;
	}

	public function setLatitude(float $latitude): self
	{
		$this->latitude = $latitude;

		return $this;
	}
}