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
		if(!in_array('il', $builder->getAllAliases())){
			$builder->innerJoin('i.itemsLanguages', 'il');
		}
		return $builder
			->andWhere('il.text LIKE :query')
			->setParameter("query","%$this->query%");
	}
}