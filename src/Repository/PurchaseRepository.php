<?php

namespace App\Repository;

use App\Entity\Purchase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Purchase|null find($id, $lockMode = null, $lockVersion = null)
 * @method Purchase|null findOneBy(array $criteria, array $orderBy = null)
 * @method Purchase[]    findAll()
 * @method Purchase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PurchaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Purchase::class);
    }

    public function getQuarterlySalesByProductReport($year)
    {
        $conn = $this->getEntityManager()->getConnection();
        $results = $conn->transactional(function($conn) use(&$year) {
            $sql = "CALL get_quarterly_sales_report_by_year(:year); ;";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('year', $year);
            $stmt->execute();
            return $stmt->fetchAll();
        });
        return $results;
    }

    public function getQuarterlySalesByStoreReport($year)
    {
        $conn = $this->getEntityManager()->getConnection();
        $results = $conn->transactional(function($conn) use(&$year) {
            $sql = "CALL get_quarterly_sales_report_by_store(:year); ;";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('year', $year);
            $stmt->execute();
            return $stmt->fetchAll();
        });
        return $results;
    }

    public function getQuarterlySalesByRouteReport($year)
    {
        $conn = $this->getEntityManager()->getConnection();
        $results = $conn->transactional(function($conn) use(&$year) {
            $sql = "CALL get_quarterly_sales_report_by_route(:year); ;";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('year', $year);
            $stmt->execute();
            return $stmt->fetchAll();
        });
        return $results;
    }

    // /**
    //  * @return Purchase[] Returns an array of Purchase objects
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
    public function findOneBySomeField($value): ?Purchase
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
