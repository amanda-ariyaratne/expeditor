<?php

namespace App\Repository;

use App\Entity\ContactNo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ContactNo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactNo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactNo[]    findAll()
 * @method ContactNo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactNoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactNo::class);
    }

    // /**
    //  * @return ContactNo[] Returns an array of ContactNo objects
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
    public function findOneBySomeField($value): ?ContactNo
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
