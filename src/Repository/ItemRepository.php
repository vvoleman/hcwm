<?php

namespace App\Repository;

use App\Entity\Collection;
use App\Entity\Item;
use App\Entity\Language;
use App\Service\Collections\Filter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Item::class);
	}

	/**
	 * @param string $id
	 * @param bool $flush
	 * @return Item
	 */
	public function getOrCreate(string $id, bool $flush = false): Item
	{
		$item = $this->find($id);

		if (!$item) {
			$item = (new Item())->setId($id);
			$this->_em->persist($item);
			if ($flush) {
				$this->_em->flush();
			}
		}

		return $item;
	}

	/**
	 * @param string[] $tags
	 * @param string[] $languages
	 * @param int|null $limit
	 * @param int|null $offset
	 * @param string $orderDirection
	 * @return int|mixed|string
	 */
	public function getFiltered(
		bool $isDirty,
		array $tags = [],
		array $languages = [],
		string $query = null,
		Collection $parent = null,
		int $limit = null,
		int $offset = null,
		string $orderDirection = Filter::ORDER_ASC
	) {
		$q = $this->createQueryBuilder('i')
			->groupBy("i.id");


		if($parent) {
			if($isDirty){
				$ids = $this->getAllSubcollections($parent);
				$q->andWhere(
					$q->expr()->in('i.collection', $ids)
				);
			} else {
				$q->andWhere('i.collection = :parent')
					->setParameter('parent', $parent);
			}


		} elseif (!$isDirty) {
			$q->andWhere('i.collection IS NULL');
		}

		if (count($tags) > 0) {
			$q
				->innerJoin('i.tags', 't')
				->andWhere(
					$q->expr()->in('t.id', $tags)
				)
				->andHaving('COUNT(t.id) = :count')
				->setParameter('count',count($tags));
		}

		$countLanguage = count($languages);
		if ($countLanguage > 0 || !!$query) {
			$q->innerJoin('i.itemsLanguages', 'il');
			if ($countLanguage > 0) {
				$q
					->andWhere('il.language IN (:languages)')
					->andHaving('COUNT(il.language) = :count')
					->setParameter('languages', $languages)
					->setParameter("count",count($languages));
			}
			if (!!$query) {
				$q->andWhere('il.text LIKE :query')
					->setParameter("query","%$query%");
			}
		}

		if (!!$limit) {
			$q->setMaxResults($limit);
		}

		if (!!$offset) {
			$q->setFirstResult($limit);
		}
		return $q->getQuery()->getResult();
	}

	/**
	 * @throws ORMException
	 * @throws OptimisticLockException
	 */
	public function add(Item $entity, bool $flush = true): void
	{
		$this->_em->persist($entity);
		if ($flush) {
			$this->_em->flush();
		}
	}

	/**
	 * @throws ORMException
	 * @throws OptimisticLockException
	 */
	public function remove(Item $entity, bool $flush = true): void
	{
		$this->_em->remove($entity);
		if ($flush) {
			$this->_em->flush();
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
