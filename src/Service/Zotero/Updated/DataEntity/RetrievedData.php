<?php

namespace App\Service\Zotero\Updated\DataEntity;

use App\Service\Zotero\Updated\Entity\Collection;
use App\Service\Zotero\Updated\Entity\Item;

class RetrievedData
{
	/** @var array<string, Collection>  */
	private array $collections = [];


	public function addCollection(Collection $collection): void
	{
		$this->collections[$collection->getKey()] = $collection;
	}

	public function addItem(Item $item): void
	{
		$this->collections[$item->getParentKey()]->addItem($item);
	}

	/**
	 * @return Collection[]
	 */
	public function getSortedCollections(): array
	{
		$sorted = [];
		foreach ($this->collections as $collection) {
			if (!$collection->getParentKey()) {
				$sorted[] = $collection;
			} else {
				$this->collections[$collection->getParentKey()]->addItem($collection);
			}
		}
		return $sorted;
	}
}