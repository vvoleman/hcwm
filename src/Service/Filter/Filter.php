<?php
declare(strict_types=1);


namespace App\Service\Filter;

use App\Entity\Item;
use App\Repository\ItemRepository;

class Filter
{

	/** @var IFilterModifier[]  */
	private array $modifiers;
	private ItemRepository $itemRepository;

	/**
	 * @param IFilterModifier[] $modifiers
	 */
	public function __construct(ItemRepository $itemRepository, array $modifiers) {
		$this->modifiers = $modifiers;
		$this->itemRepository = $itemRepository;
	}

	/**
	 * @return Item[]
	 */
	public function run(): array
	{
		$qb = $this->itemRepository->createQueryBuilder('i');

		foreach ($this->modifiers as $modifier) {
			$qb = $modifier->process($qb);
		}

		return $qb
			->getQuery()
			->getResult();
	}

}