<?php

namespace App\Entity\Geography;

use App\Entity\Geography\Trash\TrashRecordCountry;
use App\Entity\Geography\Trash\TrashRecordRegion;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\Geography\CountryRepository")]
#[ORM\Table("countries")]
class Country
{

	#[ORM\Id]
	#[ORM\Column(type: "string")]
	private string $id;

	#[ORM\Column(type: "string")]
	private string $name;

	#[ORM\OneToMany(mappedBy: "country", targetEntity: "App\Entity\Geography\Region")]
	private Collection $regions;

	#[ORM\OneToMany(mappedBy: 'country', targetEntity: TrashRecordCountry::class)]
	private Collection $trashes;

	public function __construct(string $id)
	{
		$this->regions = new ArrayCollection();
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

	/**
	 * @return Collection<int, Country>
	 */
	public function getRegions(): Collection
	{
		return $this->regions;
	}

	public function addRegion(Region $region): self
	{
		if (!$this->regions->contains($region)) {
			$this->regions[] = $region;
			$region->setCountry($this);
		}

		return $this;
	}

	public function removeRegion(Region $region): self
	{
		if ($this->regions->removeElement($region)) {
			// set the owning side to null (unless already changed)
			if ($region->getCountry() === $this) {
				$region->setCountry(null);
			}
		}

		return $this;
	}

	/**
	 * @return Collection<int, TrashRecordRegion>
	 */
	public function getTrashes(): Collection
	{
		return $this->trashes;
	}

}