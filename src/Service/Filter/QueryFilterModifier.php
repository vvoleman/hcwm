<?php
declare(strict_types=1);


namespace App\Service\Filter;

use Doctrine\ORM\QueryBuilder;

class QueryFilterModifier implements IFilterModifier
{

	private string $query;

	public function __construct(string $query) {
		$this->query = $query;
	}

	public function process(QueryBuilder $builder): QueryBuilder
	{
		return $builder->innerJoin('i.itemsLanguages', 'il')
			->andWhere('il.text LIKE :query')
			->setParameter("query","%$this->query%");
	}
}