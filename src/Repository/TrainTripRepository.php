<?php

namespace App\Repository;

use App\Entity\TrainTrip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TrainTrip|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrainTrip|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrainTrip[]    findAll()
 * @method TrainTrip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrainTripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainTrip::class);
    }

    // /**
    //  * @return TrainTrip[] Returns an array of TrainTrip objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TrainTrip
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
