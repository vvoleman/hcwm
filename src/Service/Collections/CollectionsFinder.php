<?php
declare(strict_types=1);

namespace App\Service\Collections;

use App\Entity\Collection;
use App\Entity\Item;
use App\Exception\CollectionNotFoundException;
use App\Repository\CollectionLanguageRepository;
use App\Repository\CollectionRepository;
use App\Repository\LanguageRepository;
use App\Service\Util\NormalizeChars;

class CollectionsFinder
{

	private CollectionRepository $collectionRepository;
	private CollectionLanguageRepository $languageRepository;

	public function __construct(
		CollectionRepository $collectionRepository,
		CollectionLanguageRepository $languageRepository
	) {
		$this->collectionRepository = $collectionRepository;
		$this->languageRepository = $languageRepository;
	}

	/**
	 * @param bool $isDirty
	 * @param Collection|null $parent
	 * @param Item[] $items
	 * @return Collection[]
	 */
	public function findSubcollections(bool $isDirty, ?Collection $parent = null, array $items = []): array
	{
		$collections = [];
		$allSubs = $this->getAllSubcollections($parent);
		if ($isDirty) {
			foreach ($items as $item) {
				$id = $item->getCollection()->getId();

				if($parent && $id === $parent->getId()) {
					continue;
				}

				if (in_array($id, $allSubs)) {
					$top = null;
					$upperParent = $item->getCollection();
					$temp = $upperParent;
					while(!$top) {
						if($upperParent?->getId() === $parent?->getId()){
							$top = $temp;
							break;
						} else {
							$temp = $upperParent;
							$upperParent = $upperParent->getParent();
						}
					}
					$collections[$top->getId()] = $top;
				}
			}
			$collections = array_values($collections);
		} else {
			$collections = $this->collectionRepository->findBy([
				"parent" => $parent
			]);
		}

		return $collections;
	}

	/**
	 * Returns array with keys 'parent' and 'child'. Parent is Collection, child is Collection[]
	 *
	 * @param string $string
	 * @return array<string,mixed>
	 * @throws CollectionNotFoundException
	 */
	public function find(string $string): array
	{
		$string = ltrim($string, '/');

		$string = NormalizeChars::normalize(str_replace("-", " ", $string));

		if ($string == "") {
			return [
				"parent" => null,
				"children" => $this->collectionRepository->findBy([
					"parent" => null
				])
			];
		}

		$parts = explode('/', $string);
		$part = array_pop($parts);
		$colLang = $this->languageRepository->findOneBy([
			"text" => $part
		]);

		$collection = $colLang?->getCollection();

		if (!$collection) {
			throw new CollectionNotFoundException(sprintf("Unable to find collection '%s'", $part));
		}

		return [
			"parent" => $collection,
			"children" => $collection->getSubcollections()
		];
	}

	private function getAllSubcollections(?Collection $collection): array
	{
		if ($collection == null) {
			return array_map(function ($collection) {
				return $collection->getId();
			}, $this->collectionRepository->findAll());
		}

		$queue = [$collection];
		$ids = [];
		$counter = 0;

		while (isset($queue[$counter])) {
			$ids[] = $queue[$counter]->getId();
			$queue = array_merge($queue, $queue[$counter]->getSubcollections()->toArray());
			$counter++;
		}

		return $ids;
	}

}