<?php
declare(strict_types=1);


namespace App\Service\Filter;

use Doctrine\ORM\QueryBuilder;

class CategoryFilterModifier implements IFilterModifier
{

	private array $categories;

	public function __construct(array $categories) {
		$this->categories = $categories;
	}

	public function process(QueryBuilder $builder): QueryBuilder
	{
		return $builder
			->innerJoin('i.tags', 't')
			->andWhere(
				$builder->expr()->in('t.id', $this->categories)
			)
			->andHaving('COUNT(t.id) = :count')
			->groupBy('i.id')
			->setParameter('count',count($this->categories));
	}
}