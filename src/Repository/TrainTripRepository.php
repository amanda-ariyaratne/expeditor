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

    public function getById($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$id) {
            $sql = "SELECT * FROM train_trip WHERE id = :id AND deleted_at IS NULL LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->execute();
            return $stmt->fetch();
        });
        return $this->getEntity($result);
    }

    private function getEntity($array)
    {
        $train_trip = new TrainTrip();
        $train_trip->setId($array['id']);
        $train_trip->setAllowedCapacity($array['allowed_capacity']);
        $train_trip->setStartTime(new \DateTime($array['start_time']));
        
       /* 
        $train_trip = $this->getEntityManager() 
                    ->getRepository(Store::class)
                    ->getById($array['assigned_store']);
        $train_trip->setStore(new Store ($array['assigned_store']));
        */
        $train_trip->setCreatedAt(new \DateTime($array['created_at']));
        $train_trip->setUpdatedAt(new \DateTime($array['updated_at']));
        $train_trip->setDeletedAt(new \DateTime($array['deleted_at']));
        return $train_trip;
    }

    public function assignToTrainTrip($purchase_id){
        //get purchase and products to calculate product total_size
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM purchase_status_address_purchaseproduct_product WHERE id =:id;";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('id', $purchase_id);
        $stmt->execute();
        $purchases =  $stmt->fetchAll();

        $total_size = 0;
        foreach($purchases as $p){
            $total_size += $p["quantity"] * $p["size"];
        }

        //get train trip
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM train_trip WHERE `date` > :created AND allowed_capacity>=:total_size AND deleted_at IS NULL ORDER BY `date` DESC LIMIT 1;";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('created', $purchases[0]["created"]);
        $stmt->bindValue('total_size', $total_size);
        $stmt->execute();
        $available_train_trip =  $stmt->fetch();

        
        if(count($available_train_trip) != 0){
            //update purchase with train trip id
            $conn = $this->getEntityManager()->getConnection();
            $sql = "UPDATE purchase SET train_trip_id =:train_trip_id WHERE id = :id ;";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('train_trip_id', $available_train_trip["id"]);
            $stmt->bindValue('id', $purchase_id);
            $stmt->execute();

            //update train trip allowed_capacity
            $conn = $this->getEntityManager()->getConnection();
            $sql = "UPDATE train_trip SET allowed_capacity =:new_capacity WHERE id = :id ;";
            $stmt = $conn->prepare($sql);
            $new_capacity = $available_train_trip["allowed_capacity"] - $total_size;
            $stmt->bindValue('new_capacity', $new_capacity);
            $stmt->bindValue('id', $available_train_trip["id"]);
            $stmt->execute();

            return true;
        }
        return false;
    }
}
