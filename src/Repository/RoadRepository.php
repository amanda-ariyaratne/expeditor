<?php

namespace App\Repository;

use App\Entity\Road;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Road|null find($id, $lockMode = null, $lockVersion = null)
 * @method Road|null findOneBy(array $criteria, array $orderBy = null)
 * @method Road[]    findAll()
 * @method Road[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Road::class);
    }

    // /**
    //  * @return Road[] Returns an array of Road objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Road
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
