<?php

declare(strict_types=1);


namespace App\Service\Zotero\Updated\Entity;

use App\Service\Zotero\Updated\Exception\Entity\DuplicateChildException;
use App\Service\Zotero\Updated\Exception\Entity\InvalidParentException;

class Collection extends ZoteroEntity
{

	/** @var ZoteroEntity[] */
	private array $items = [];

	/**
	 * @throws InvalidParentException
	 * @throws DuplicateChildException
	 */
	public function addItem(ZoteroEntity $entity): void
	{
		if (ZoteroEntity::isChildOf($this, $entity)) {
			throw new InvalidParentException(
				sprintf('Entity "%s" is not parent of "%s"', $this->getKey(), $entity->getKey())
			);
		}

		if (isset($this->items[$entity->getKey()])) {
			throw new DuplicateChildException(
				sprintf('Child "%s" is already in "%s', $entity->getKey(), $this->getKey())
			);
		}

		$this->items[$entity->getKey()] = $entity;
	}
}