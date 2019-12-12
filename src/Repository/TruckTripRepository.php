<?php

namespace App\Repository;

use App\Entity\TruckTrip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TruckTrip|null find($id, $lockMode = null, $lockVersion = null)
 * @method TruckTrip|null findOneBy(array $criteria, array $orderBy = null)
 * @method TruckTrip[]    findAll()
 * @method TruckTrip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TruckTripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TruckTrip::class);
    }

    // /**
    //  * @return TruckTrip[] Returns an array of TruckTrip objects
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
    public function findOneBySomeField($value): ?TruckTrip
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
