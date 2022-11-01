<?php

namespace App\Service\Zotero\DataEntity;

use App\Service\Zotero\Entity\Collection;
use App\Service\Zotero\Entity\Item;
use App\Service\Zotero\Exception\Entity\ZoteroEntityException;

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
		$parentKey = $item->getParentKey();
		if (!isset($this->collections[$parentKey])) {
			throw new ZoteroEntityException('RetrievedData doesn\'t contain collection with key ' . $parentKey);
		}

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