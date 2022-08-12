<?php

namespace App\Entity\Geography;

use App\Entity\Geography\Trash\TrashRecordRegion;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\Geography\RegionRepository")]
#[ORM\Table("regions")]
class Region
{

	#[ORM\Id]
	#[ORM\Column(type: "string")]
	private string $id;

	#[ORM\Column(type: "string")]
	private string $name;

	#[ORM\ManyToOne(targetEntity: "App\Entity\Geography\Country", inversedBy: "regions")]
	#[ORM\JoinColumn(referencedColumnName: "id", onDelete: "CASCADE")]
	private Country $country;

	#[ORM\OneToMany(mappedBy: "region", targetEntity: "App\Entity\Geography\District")]
	private Collection $districts;

	#[ORM\Column(type: 'float')]
	private float $longitude;

	#[ORM\Column(type: 'float')]
	private float $latitude;

	#[ORM\OneToMany(mappedBy: 'region', targetEntity: TrashRecordRegion::class)]
	private Collection $trashes;

	public function __construct(string $id)
	{
		$this->districts = new ArrayCollection();
		$this->id = $id;
		$this->trashes = new ArrayCollection();
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

	public function getCountry(): ?Country
	{
		return $this->country;
	}

	public function setCountry(?Country $country): self
	{
		$this->country = $country;

		return $this;
	}

	/**
	 * @return Collection<int, District>
	 */
	public function getDistricts(): Collection
	{
		return $this->districts;
	}

	public function addDistrict(District $district): self
	{
		if (!$this->districts->contains($district)) {
			$this->districts[] = $district;
			$district->setRegion($this);
		}

		return $this;
	}

	public function removeDistrict(District $district): self
	{
		if ($this->districts->removeElement($district)) {
			// set the owning side to null (unless already changed)
			if ($district->getRegion() === $this) {
				$district->setRegion(null);
			}
		}

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

	/**
	 * @return Collection<int, TrashRecordRegion>
	 */
	public function getTrashes(): Collection
	{
		return $this->trashes;
	}

	public function addTrash(TrashRecordRegion $trash): self
	{
		if (!$this->trashes->contains($trash)) {
			$this->trashes[] = $trash;
			$trash->setRegion($this);
		}

		return $this;
	}

	public function removeTrash(TrashRecordRegion $trash): self
	{
		if ($this->trashes->removeElement($trash)) {
			// set the owning side to null (unless already changed)
			if ($trash->getRegion() === $this) {
				$trash->setRegion(null);
			}
		}

		return $this;
	}

}