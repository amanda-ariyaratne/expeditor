<?php

namespace App\Repository;

use App\Entity\Truck;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

use App\Entity\Store;

/**
 * @method Truck|null find($id, $lockMode = null, $lockVersion = null)
 * @method Truck|null findOneBy(array $criteria, array $orderBy = null)
 * @method Truck[]    findAll()
 * @method Truck[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TruckRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Truck::class);
    }

    public function getAll()
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) {
            $sql = "CALL getTrucks(0);";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        });
        
        return $this->getEntityArray($result);
    }

    public function getAllByStore($store_id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$store_id) {
            $sql = "CALL getTrucks(:store_id);";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('store_id',$store_id);
            $stmt->execute();
            return $stmt->fetchAll();
        });
        
        return $this->getEntityArray($result);
    }
    public function getByStoreAndTime($stime,$max_time,$_date,$store_id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$store_id,&$_date,&$stime,&$max_time) {
            $sql = 'CALL get_truck_trips(:stime,:max_time,:_date,:store_id);';
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('store_id', $store_id);
            $stmt->bindValue('_date', $_date,'date');
            $stmt->bindValue('max_time', $max_time);
            $stmt->bindValue('stime', $stime,'time');
            
            $stmt->execute();
            return $stmt->fetchAll();
        });
        return $this->getEntityArrayforIndex($result);
    }

    public function getById($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$id) {
            $sql = "SELECT * from truck where id=:id and  deleted_at IS NULL; ;";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->execute();
            return $stmt->fetch();
        });
        return $this->getEntityforIndex($result);
    }

    public function insert(Truck $truck)
    {
        $conn = $this->getEntityManager()->getConnection();
        $lastInsertId = $conn->transactional(function($conn) use(&$truck) {
            $sql = "INSERT INTO truck (registration_no, insurance_no, store_id) VALUES (:register, :insurance, :store);";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('insurance', $truck->getInsuranceNo());
            $stmt->bindValue('register', $truck->getRegistrationNo());
            $stmt->bindValue('store', $truck->getStore() == null ? null : $truck->getStore()->getId());
            $stmt->execute();
            return $conn->lastInsertId();
        });
        return $lastInsertId;
    }

    public function update(Truck $truck)
    {
        $conn = $this->getEntityManager()->getConnection();
        $status = $conn->transactional(function($conn) use(&$truck) {
            $sql = "UPDATE truck SET registration_no=:register, insurance_no=:insurance, store_id=:store WHERE id=:id AND deleted_at IS NULL";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('insurance', $truck->getInsuranceNo());
            $stmt->bindValue('register', $truck->getRegistrationNo());
            $stmt->bindValue('store', $truck->getStore() == null ? null : $truck->getStore()->getId());
            $stmt->bindValue('id', $truck->getId());
            $stmt->execute();
            return true;
        });
        return $status;
    }

    public function deleteById($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $status = $conn->transactional(function($conn) use(&$id) {
            $sql = "UPDATE truck SET deleted_at = now() WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
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
    private function getEntityArrayforIndex($array)
    {
        $entityArray = [];
        foreach ($array as $element) {
            array_push($entityArray, $this->getEntityforIndex($element));
        }
        return $entityArray;
    }

    private function getEntityforIndex($params)
    {
        $truck = new Truck();
        $truck->setId($params['id']);
        $truck->setInsuranceNo($params['insurance_no']);
        $truck->setRegistrationNo($params['registration_no']);
        
        
        $store = $this->getEntityManager() 
                    ->getRepository(Store::class)
                    ->getById($params['store_id']);
        $truck->setStore($store);    
        $truck->setCreatedAt(new \DateTime($params['created_at']));
        $truck->setUpdatedAt(new \DateTime($params['updated_at']));
        return $truck;
    }

    private function getEntity($params)
    {
        $truck = new Truck();
        $truck->setId($params['truck_id']);
        $truck->setInsuranceNo($params['truck_insurance_no']);
        $truck->setRegistrationNo($params['truck_registration_no']);
        
        $storeArray = [
            'id' => $params['store_id'],
            'name' => $params['store_name'],
            'street' => $params['store_street'],
            'city' => $params['store_city'],
            'created_at' => $params['store_created_at'],
            'updated_at' => $params['store_updated_at'],
            'deleted_at' => $params['store_deleted_at']
        ];
        $store = $this->getEntityManager() 
                    ->getRepository(Store::class)
                    ->getEntity($storeArray);

        $truck->setWorkedHours($params['worked_hours']);
        $truck->setStore($store);     
        $truck->setCreatedAt(new \DateTime($params['truck_created_at']));
        $truck->setUpdatedAt(new \DateTime($params['truck_updated_at']));
        return $truck;
    }
}
