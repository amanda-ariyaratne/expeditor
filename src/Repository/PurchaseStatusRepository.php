<?php

namespace App\Repository;

use App\Entity\PurchaseStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PurchaseStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method PurchaseStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method PurchaseStatus[]    findAll()
 * @method PurchaseStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PurchaseStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PurchaseStatus::class);
    }

    // /**
    //  * @return PurchaseStatus[] Returns an array of PurchaseStatus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PurchaseStatus
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
