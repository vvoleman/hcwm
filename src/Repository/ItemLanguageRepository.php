<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\ItemLanguage;
use App\Entity\Language;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ItemLanguage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemLanguage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemLanguage[]    findAll()
 * @method ItemLanguage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemLanguageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemLanguage::class);
    }

    public function getOrCreate(Item $item, Language $language): ItemLanguage
    {
        $itemLang = $this->find([
            "item"=>$item->getId(),
            "language"=>$language->getCode()
        ]);
        if(!$itemLang){
            $itemLang = (new ItemLanguage())
                ->setLanguage($language)
                ->setItem($item);
        }

        return $itemLang;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ItemLanguage $entity, bool $flush = true): void
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
    public function remove(ItemLanguage $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return ItemLanguage[] Returns an array of ItemLanguage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ItemLanguage
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
