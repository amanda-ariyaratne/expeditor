<?php
namespace App\Repository;
use App\Entity\TrainTrip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\Store;
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
    public function getAll()
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) {
            $sql = "SELECT * FROM train_trip WHERE deleted_at IS NULL;";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        });
        return $this->getEntityArray($result);
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
    
    public function insert(TrainTrip $train_trip)
    {
        $conn = $this->getEntityManager()->getConnection();
        $lastInsertId = $conn->transactional(function($conn) use(&$train_trip) {
            
            $sql = "INSERT INTO train_trip (allowed_capacity,start_time,date) VALUES (:allowed_capacity, :start_time, :_date);";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('allowed_capacity', $train_trip->getAllowedCapacity());
            $stmt->bindValue('start_time', $train_trip->getStartTime(),'time');
            $stmt->bindValue('_date', $train_trip->getDate(),'date');
            $stmt->execute();
            $lastInsertedId = $conn->lastInsertId();
            
            $stores = $train_trip->getStore();            
            for($i = 0; $i < count($stores); $i++){
                $sql = "INSERT INTO train_trip_store (train_trip_id, store_id) VALUES (:train_trip_id, :store_id);" ;
                $stmt = $conn->prepare($sql);
                $stmt->bindValue('train_trip_id', $lastInsertedId);
                $stmt->bindValue('store_id', $stores[$i]->getId());
                $stmt->execute();
            }
        });
    }
    public function update(TrainTrip $train_trip)
    {
        $date = new \DateTime();
        $date = $date->format('Y-m-d H:i:s');
        $conn = $this->getEntityManager()->getConnection();
        $status = $conn->transactional(function($conn) use(&$train_trip,&$date) {
            $sql = "UPDATE train_trip SET allowed_capacity =:allowed_capacity,start_time =:start_time WHERE id=:id AND deleted_at IS NULL";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $train_trip->getId());
            $stmt->bindValue('allowed_capacity', $train_trip->getAllowedCapacity());
            $stmt->bindValue('start_time', $train_trip->getStartTime(),'time');  
            //$stmt->bindValue('_date', $train_trip->getStartTime(),'date');           
            $stmt->execute();
            $stores = $train_trip->getStore(); 
            $sql = "DELETE FROM train_trip_store  WHERE train_trip_id=:id AND deleted_at IS NULL ;" ;
            //dump($stores[0]);
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $train_trip->getId());
            
            $stmt->execute();
            for($i = 0; $i < count($stores); $i++){
                $sql = "INSERT INTO train_trip_store (train_trip_id, store_id) VALUES (:id, :store_id);" ;
                $stmt = $conn->prepare($sql);
                //dump($stores[$i]);
                $stmt->bindValue('id', $train_trip->getId());
                $stmt->bindValue('store_id', $stores[$i]->getId());
                
                $stmt->execute();
            }
        });
        
    }
    public function delete($id)
    {
        $date = new \DateTime();
        $date = $date->format('Y-m-d H:i:s');
        $conn = $this->getEntityManager()->getConnection();
        $status = $conn->transactional(function($conn) use(&$id, &$date) {
            $sql = "UPDATE train_trip SET deleted_at =:present  WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->bindValue('present', $date);
            $stmt->execute();
                       
            
                $sql = "UPDATE train_trip_store SET deleted_at =:present WHERE train_trip_id=:id AND deleted_at IS NULL ;" ;
                $stmt = $conn->prepare($sql);
                $stmt->bindValue('id', $id);
                $stmt->bindValue('present', $date);
                $stmt->execute();
            
            return true;
        });
        return $status;
    }
    private function getEntityArray($array)
    {
        $entityArray = [];
        foreach ($array as $element) {
            array_push($entityArray, $this->getEntity($element));
        }
        return $entityArray;
    }
    private function getEntity($array)
    {
        $train_trip = new TrainTrip();
        $train_trip->setId($array['id']);
        $train_trip->setAllowedCapacity($array['allowed_capacity']);
        $train_trip->setStartTime(new \DateTime($array['start_time']));
        $train_trip->setDate(new \DateTime($array['date']));
        $stores =  $this->getEntityManager()
                        ->getRepository(Store::class)
                        ->getStoreOfTrainTrip($array['id']);  
        foreach($stores as $store){
            $train_trip->addStore($store);
        }   
        
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