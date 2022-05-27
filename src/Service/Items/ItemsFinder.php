<?php declare(strict_types = 1);

namespace App\Service\Items;

use App\Entity\Item;
use App\Repository\ItemRepository;

class ItemsFinder
{

	private ItemRepository $itemRepository;

	public function __construct(ItemRepository $itemRepository) {
		$this->itemRepository = $itemRepository;
	}

	/**
	 * @param int $number
	 * @return Item[]
	 */
	public function getRecentCollections(int $number): array
	{
		$qb = $this->itemRepository->createQueryBuilder('i');
		return $qb
			->orderBy('i.dateModified','DESC')
			->setMaxResults($number)
			->getQuery()
			->getResult();
	}

}