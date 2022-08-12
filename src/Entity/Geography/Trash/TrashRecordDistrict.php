<?php

namespace App\Entity\Geography\Trash;

use App\Entity\Geography\District;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\Geography\Trash\TrashRecordDistrictRepository")]
#[ORM\Table("trash_records_district")]
class TrashRecordDistrict extends AbstractTrashRecord
{
	#[ORM\Id]
   	#[ORM\GeneratedValue]
   	#[ORM\Column(type: "integer")]
   	private int $id;

	#[ORM\Column(type: 'integer')]
   	private int $year;

	#[ORM\Column(type: 'float')]
   	private float $value;

	#[ORM\ManyToOne(targetEntity: TrashType::class)]
   	private TrashType $trashType;

	#[ORM\ManyToOne(targetEntity: District::class, inversedBy: 'trashes')]
   	private District $district;

	public function getId(): ?int
   	{
   		return $this->id;
   	}

	public function getYear(): int
   	{
   		return $this->year;
   	}

	public function setYear(int $year): self
   	{
   		$this->year = $year;
   
   		return $this;
   	}

	public function getValue(): float
   	{
   		return $this->value;
   	}

	public function setValue(float $value): self
   	{
   		$this->value = $value;
   
   		return $this;
   	}

	public function getDistrict(): ?District
   	{
   		return $this->district;
   	}

	public function setDistrict(?District $district): self
   	{
   		$this->district = $district;
   
   		return $this;
   	}

	public function setTrashType(TrashType $trashType): self
   	{
   		$this->trashType = $trashType;
   
   		return $this;
   	}

    public function getTrashType(): TrashType
    {
        return $this->trashType;
    }

}