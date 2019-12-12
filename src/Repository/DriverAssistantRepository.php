<?php

namespace App\Repository;

use App\Entity\DriverAssistant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method DriverAssistant|null find($id, $lockMode = null, $lockVersion = null)
 * @method DriverAssistant|null findOneBy(array $criteria, array $orderBy = null)
 * @method DriverAssistant[]    findAll()
 * @method DriverAssistant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DriverAssistantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DriverAssistant::class);
    }

    // /**
    //  * @return DriverAssistant[] Returns an array of DriverAssistant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DriverAssistant
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
