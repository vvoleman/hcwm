<?php

namespace App\Entity\Geography\Trash;

use App\Entity\Geography\District;
use App\Entity\Geography\Region;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\Geography\Trash\TrashRecordRegionRepository")]
#[ORM\Table("trash_records_region")]
class TrashRecordRegion extends AbstractTrashRecord
{
	#[ORM\Id]
   	#[ORM\GeneratedValue]
   	#[ORM\Column(type: "integer")]
   	private int $id;

	#[ORM\Column(type: 'integer')]
   	private int $year;

	#[ORM\Column(type: 'float')]
   	private float $value = 0;

	#[ORM\ManyToOne(targetEntity: Region::class, inversedBy: 'trashes')]
   	private Region $region;

	#[ORM\ManyToOne(targetEntity: TrashType::class)]
   	private TrashType $trashType;

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

	public function getRegion(): ?Region
   	{
   		return $this->region;
   	}

	public function setRegion(?Region $region): self
   	{
   		$this->region = $region;
   
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