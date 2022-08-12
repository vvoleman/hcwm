<?php

namespace App\Entity\Geography\Trash;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\Geography\Trash\TrashRecordRepository")]
#[ORM\Table("trash_records")]
class TrashRecord
{
	#[ORM\Id]
               	#[ORM\GeneratedValue]
               	#[ORM\Column(type: "integer")]
               	private int $id;

	#[ORM\Column(type: 'integer')]
               	private int $year;

	#[ORM\Column(type: 'float')]
               	private float $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

}