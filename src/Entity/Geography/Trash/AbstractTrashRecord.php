<?php

namespace App\Entity\Geography\Trash;

abstract class AbstractTrashRecord
{

	public abstract function getValue(): float;

	public abstract function getYear(): int;

	public abstract function getTrashType(): TrashType;

	public function applyData(array $data): void
	{
		$data[$this->getYear()] = $this->getValue();
	}


}