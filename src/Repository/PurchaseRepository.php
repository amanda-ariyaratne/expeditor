<?php

namespace App\Repository;

use App\Entity\Purchase;
use App\Entity\PurchaseStatus;
use App\Entity\Customer;
use App\Entity\Store;
use App\Entity\TrainTrip;
use App\Entity\Address;
use App\Entity\TruckRoute;

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

    public function getAllByCustomerID($id): ?Array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM purchase WHERE customer_id = :id AND deleted_at IS NULL";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByCustomerId($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$id) {
            $sql = "SELECT * FROM `purchase` WHERE customer_id = :id AND deleted_at IS NULL LIMIT 1;";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->execute();
            return $stmt->fetch();
        });
        return $this->getEntity($result);
    }


    public function getDetailsByPurchaseID($id): ?Array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM `purchase_status_address_purchaseproduct_product` WHERE id = :id ;";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
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


    public function getEntity($params){
        $purchase = new Purchase();
        $purchase->setId($params['id']);
        $purchase->setStatus($this->getEntityManager()->getRepository(PurchaseStatus::class)->getByID($params['status_id']));
        $purchase->setDeliveryDate(new \DateTime($params['delivery_date']));
        $purchase->setCustomer($this->getEntityManager()->getRepository(Customer::class)->getByID($params['customer_id']));
        $purchase->setTruckRoute($this->getEntityManager()->getRepository(TruckRoute::class)->getTruckRouteById($params['truck_route_id']));
        $purchase->setStore($this->getEntityManager()->getRepository(Store::class)->getById($params['store_id']));
        //$purchase->setTrainTrip($this->getEntityManager()->getRepository(TrainTrip::class)->getById($params['train_trip_id']));
        $purchase->setTrainTrip(null);
        $purchase->setAddress($this->getEntityManager()->getRepository(Address::class)->getById($params['address_id']));

        $purchase->setCreatedAt(new \DateTime($params['created_at']));
        $purchase->setUpdatedAt(new \DateTime($params['updated_at']));
        $purchase->setDeletedAt(new \DateTime($params['deleted_at']));
        return $purchase;

    }



    
}
