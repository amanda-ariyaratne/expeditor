<?php

namespace App\Repository;

use App\Entity\StoreManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method StoreManager|null find($id, $lockMode = null, $lockVersion = null)
 * @method StoreManager|null findOneBy(array $criteria, array $orderBy = null)
 * @method StoreManager[]    findAll()
 * @method StoreManager[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StoreManagerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoreManager::class);
    }

    // /**
    //  * @return StoreManager[] Returns an array of StoreManager objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StoreManager
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
