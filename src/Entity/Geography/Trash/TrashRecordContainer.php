<?php

namespace App\Entity\Geography\Trash;

class TrashRecordContainer
{

	/** @var AbstractTrashRecord[] $records  */
	private array $records = [];

	public function add(AbstractTrashRecord $record): void
	{
		$this->records[] = $record;
	}
	
	public function getFormattedArray(): array
	{
		$results = [];

		foreach ($this->records as $record) {
			$trashId = $record->getTrashType()->getId();
			if (!array_key_exists($trashId, $results)) {
				$results[$trashId] = [];
			}

			$results[$trashId][$record->getYear()] = $record->getValue();
		}

		return $results;
	}

	/**
	 * @param AbstractTrashRecord[] $records
	 * @return self
	 */
	public static function makeFromArray(array $records): self {
		$container = new self();

		foreach ($records as $record) {
			$container->add($record);
		}

		return $container;
	}

}