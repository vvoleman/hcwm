<?php

namespace App\Entity\Geography\Trash;

class TrashRecordContainer
{

	public const OTHERS = 'others';

	/** @var AbstractTrashRecord[] $records  */
	private array $records = [];

	private array $allowedTypes = [];


	public function __construct() {
		$this->setAllowedTypes(self::getAllowedTypes());
	}

	/**
	 * Set allowed trash types, other will be summed together
	 * @param array $types Leave empty for all allowed
	 * @return void
	 */
	public function setAllowedTypes(array $types = []): void
	{
		$this->allowedTypes = $types;
	}

	public function add(AbstractTrashRecord $record): void
	{
		$this->records[] = $record;
	}
	
	public function getFormattedArray(): array
	{
		$results = [];

		foreach ($this->records as $record) {
			$trashId = $this->getTranslatedType($record->getTrashType()->getId());

			if (!array_key_exists($trashId, $results)) {
				$results[$trashId] = [];
			}

			if (!array_key_exists($record->getYear(), $results[$trashId])) {
				$results[$trashId][$record->getYear()] = 0;
			}

			$results[$trashId][$record->getYear()] += $record->getValue();
		}

		ksort($results);

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

	public static function getAllowedTypes(): array
	{
		return ['F180101','F180103','F180104'];
	}


	/**
	 * @param TrashType[] $types
	 * @return array<string, TrashType[]>
	 */
	public static function filterTypes(array $types): array
	{
		$allowed = self::getAllowedTypes();

		$results = [
			'allowed' => [],
			'others' => []
		];

		foreach ($types as $type) {
			$dir = in_array($type->getId(), $allowed) ? 'allowed' : 'others';

			$results[$dir][] = $type;
		}

		return $results;
	}

	private function getTranslatedType(string $type): string
	{
		if (count($this->allowedTypes) === 0 || in_array($type, $this->allowedTypes)) {
			return $type;
		}

		return self::OTHERS;
	}

}