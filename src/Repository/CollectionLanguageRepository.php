<?php

namespace App\Repository;

use App\Entity\Collection;
use App\Entity\CollectionLanguage;
use App\Entity\Language;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CollectionLanguage|null find($id, $lockMode = null, $lockVersion = null)
 * @method CollectionLanguage|null findOneBy(array $criteria, array $orderBy = null)
 * @method CollectionLanguage[]    findAll()
 * @method CollectionLanguage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CollectionLanguageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CollectionLanguage::class);
    }

    public function getOrCreate(Collection $collection, Language $language): CollectionLanguage
    {
        $colLang = $this->find([
            "collection"=>$collection->getId(),
            "language"=>$language->getCode()
        ]);
        if(!$colLang){
            $colLang = (new CollectionLanguage())
                ->setLanguage($language)
                ->setCollection($collection);
        }

        return $colLang;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(CollectionLanguage $entity, bool $flush = true): void
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
    public function remove(CollectionLanguage $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return CollectionLanguage[] Returns an array of CollectionLanguage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CollectionLanguage
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
