<?php declare(strict_types = 1);

namespace App\Service\Filter;

use App\Entity\Collection;
use Doctrine\ORM\QueryBuilder;

class CollectionFilterModifier implements IFilterModifier
{

	private Collection $collection;
	private bool $recursive;

	public function __construct(Collection $collection, bool $recursive = false) {
		$this->collection = $collection;
		$this->recursive = $recursive;
	}

	public function process(QueryBuilder $builder): QueryBuilder
	{
		if($this->recursive) {
			$collections = $this->getAllSubcollections($this->collection);
			$collections[] = $this->collection->getId();

			return $builder->andWhere(
				$builder->expr()->in('i.collection', $collections)
			);
		} else {
			return $builder
				->andWhere('i.collection = :collection')
				->setParameter('collection', $this->collection);
		}

	}

	private function getAllSubcollections(Collection $collection): array{
		$queue = [$collection];
		$ids = [];
		$counter = 0;

		while (isset($queue[$counter])){
			$ids[] = $queue[$counter]->getId();
			$queue = array_merge($queue,$queue[$counter]->getSubcollections()->toArray());
			$counter++;
		}

		return $ids;
	}
}