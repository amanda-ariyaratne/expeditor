<?php

namespace App\Repository;

use App\Entity\TruckRoute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TruckRoute|null find($id, $lockMode = null, $lockVersion = null)
 * @method TruckRoute|null findOneBy(array $criteria, array $orderBy = null)
 * @method TruckRoute[]    findAll()
 * @method TruckRoute[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TruckRouteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TruckRoute::class);
    }

    // /**
    //  * @return TruckRoute[] Returns an array of TruckRoute objects
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
    public function findOneBySomeField($value): ?TruckRoute
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
