<?php

namespace App\Entity\Geography;

use App\Entity\Geography\Trash\TrashRecord;
use App\Entity\Geography\Trash\TrashRecordDistrict;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\Geography\DistrictRepository")]
#[ORM\Table("districts")]
class District
{

	#[ORM\Id]
                           	#[ORM\Column(type: "string")]
                           	private string $id;

	#[ORM\Column(type: "string")]
                           	private string $name;

	#[ORM\ManyToOne(targetEntity: "App\Entity\Geography\Region", inversedBy: "districts")]
                           	#[ORM\JoinColumn(referencedColumnName: "id", onDelete: "CASCADE")]
                           	private Region $region;

	#[ORM\Column(type: 'float')]
                           	private float $longitude;

	#[ORM\Column(type: 'float')]
                           	private float $latitude;

	#[ORM\OneToMany(mappedBy: 'district', targetEntity: TrashRecordDistrict::class)]
                           	private Collection $trashes;

	public function __construct(string $id) {
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

	public function getRegion(): ?Region
                           	{
                           		return $this->region;
                           	}

	public function setRegion(?Region $region): self
                           	{
                           		$this->region = $region;
                           
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
     * @return Collection<int, TrashRecordDistrict>
     */
    public function getTrashes(): Collection
    {
        return $this->trashes;
    }

    public function addTrash(TrashRecordDistrict $trash): self
    {
        if (!$this->trashes->contains($trash)) {
            $this->trashes[] = $trash;
            $trash->setDistrict($this);
        }

        return $this;
    }

    public function removeTrash(TrashRecordDistrict $trash): self
    {
        if ($this->trashes->removeElement($trash)) {
            // set the owning side to null (unless already changed)
            if ($trash->getDistrict() === $this) {
                $trash->setDistrict(null);
            }
        }

        return $this;
    }

}