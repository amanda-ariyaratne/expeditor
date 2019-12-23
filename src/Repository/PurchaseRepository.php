<?php

namespace App\Repository;

use App\Entity\Purchase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\DateTime;

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


    public function insert(Purchase $purchase)
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$purchase) {
            $sql = "INSERT INTO purchase (status_id,  delivery_date,  customer_id,  truck_route_id,  store_id,  address_id) 
                                 VALUES (:status_id, :delivery_date, :customer_id, :truck_route_id, :store_id, :address_id);";
            $stmt = $conn->prepare($sql);

            $status_id = $purchase->getStatus()->getId();
            $delivery_date = $purchase->getDeliveryDate();
            $customer_id = $purchase->getCustomer()->getUser()->getId();
            $truck_route_id = $purchase->getTruckRoute()->getId();
            $store_id = $purchase->getStore()->getId();
            $address_id = $purchase->getAddress()->getId();
            

            $newDate = $delivery_date->format('Y-m-d');

            $stmt->bindValue('status_id', $status_id);
            $stmt->bindValue('delivery_date', $newDate);
            $stmt->bindValue('customer_id', $customer_id);
            $stmt->bindValue('truck_route_id', $truck_route_id);
            $stmt->bindValue('store_id', $store_id);
            $stmt->bindValue('address_id', $address_id);

            $stmt->execute();
            return $conn->lastInsertId();
        });
        return $result;
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
