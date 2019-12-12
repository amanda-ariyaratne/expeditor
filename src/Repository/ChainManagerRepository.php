<?php

namespace App\Repository;

use App\Entity\ChainManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ChainManager|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChainManager|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChainManager[]    findAll()
 * @method ChainManager[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChainManagerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChainManager::class);
    }

    // /**
    //  * @return ChainManager[] Returns an array of ChainManager objects
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
    public function findOneBySomeField($value): ?ChainManager
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
