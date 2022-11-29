<?php

declare(strict_types=1);


namespace App\Service\Zotero\Entity;

use App\Entity\CollectionLanguage;
use App\Repository\LanguageRepository;
use App\Service\Zotero\Exception\Entity\DuplicateChildException;
use App\Service\Zotero\Exception\Entity\InvalidParentException;
use App\Service\Zotero\PrepareLanguages;
use Doctrine\ORM\EntityManagerInterface;

class Collection extends TranslatableZoteroEntity
{

	/** @var ZoteroEntity[] */
	private array $items = [];

	/**
	 * @throws InvalidParentException
	 * @throws DuplicateChildException
	 */
	public function addItem(ZoteroEntity $entity): void
	{
		if (!ZoteroEntity::isChildOf($this, $entity)) {
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

	/**
	 * @return ZoteroEntity[]
	 */
	public function getItems(): array
	{
		return $this->items;
	}

	public function makeDoctrineEntity(EntityManagerInterface $manager): \App\Entity\Collection
	{
		$collectionRepository = $manager->getRepository(\App\Entity\Collection::class);
		$collection = $collectionRepository->find($this->getKey());
		if ($collection) {
			$manager->remove($collection);
			$manager->flush();
		}

		$collection = new \App\Entity\Collection();
		$collection->setId($this->getKey());

		/** @var LanguageRepository $languageRepository */
		$languageRepository = $manager->getRepository(\App\Entity\Language::class);

		$translations = (new PrepareLanguages($languageRepository))->prepare($this->name);
		foreach ($translations as $translation) {
			$collectionLanguage = new CollectionLanguage();
			$collectionLanguage->setCollection($collection);
			$collectionLanguage->setLanguage($translation->getLanguage());
			$collectionLanguage->setText($translation->getText());

			$manager->persist($collectionLanguage);
			$collection->addCollectionsLanguage($collectionLanguage);
		}

		$manager->persist($collection);

		foreach ($this->items as $item) {
			$entity = $item->makeDoctrineEntity($manager);

			if ($item instanceof Item) {
				$collection->addItem($entity);
			} else {
				$collection->addSubcollection($entity);
			}
		}

		return $collection;

	}
}