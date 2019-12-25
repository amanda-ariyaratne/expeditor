<?php

namespace App\Repository;

use App\Entity\Driver;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\Store;

/**
 * @method Driver|null find($id, $lockMode = null, $lockVersion = null)
 * @method Driver|null findOneBy(array $criteria, array $orderBy = null)
 * @method Driver[]    findAll()
 * @method Driver[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class DriverRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Driver::class);
    }
  
    public function getAll(){
        $conn = $this->getEntityManager()->getConnection();
        $results = $conn->transactional(function($conn){
            $sql = "CALL getDrivers(0)";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        });         
        // dd($results);
        return $this->getEntityArray($results);
    }

    public function getAllByStore($store_id){
        $conn = $this->getEntityManager()->getConnection();
        $results = $conn->transactional(function($conn) use(&$store_id){
            $sql = "CALL getDrivers(:store_id)";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('store_id', $store_id);
            $stmt->execute();
            return $stmt->fetchAll();
        });         
        return $this->getEntityArray($results);
    }

    public function insert(Driver $driver)
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$driver) {
            $sql = "INSERT INTO driver (nic, license_no, first_name, last_name, store_id) VALUES (:nic, :license_no, :first_name, :last_name, :store);";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('first_name', $driver->getFirstName());
            $stmt->bindValue('last_name',$driver->getLastName());
            $stmt->bindValue('nic', $driver->getNIC());
            $stmt->bindValue('license_no', $driver->getLicenseNo());
            $stmt->bindValue('store', $driver->getStore()->getId());
            $stmt->execute();
        });
    }

    public function update($driver)
    {   
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$driver) {
            $sql = "UPDATE driver SET first_name=:first_name, last_name=:last_name, nic=:nic, license_no=:license_no, store_id=:store_id WHERE id = :id AND deleted_at IS NULL";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $driver->getId());
            $stmt->bindValue('first_name', $driver->getFirstName());
            $stmt->bindValue('last_name',$driver->getLastName());
            $stmt->bindValue('nic', $driver->getNIC());
            $stmt->bindValue('license_no', $driver->getLicenseNo());
            $stmt->bindValue('store_id', $driver->getStore()->getId());
            $stmt->execute();
        });        
    }

    public function delete($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $conn->beginTransaction();
        try{
            $sql = "UPDATE driver SET deleted_at=now() WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->execute(); 
            $conn->commit();
            return true; 
        } 
        catch (\Exception $e) {
            $conn->rollBack();
            return false;
        }
    }
  
    private function getEntity($params){
        $driver = new Driver();
        // dd($params);
        $driver->setId($params['driver_id']);
        $driver->setFirstName($params['driver_first_name']);
        $driver->setLastName($params['driver_last_name']);
        $driver->setNIC($params['driver_NIC']);
        $driver->setLicenseNo($params['driver_license_no']);
        
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

        $driver->setStore($store);
        $driver->setCreatedAt(new \DateTime($params['driver_created_at']));
        $driver->setUpdatedAt(new \DateTime($params['driver_updated_at']));
        $driver->setWorkedHours($params['worked_hours']);

        return $driver;
    }

    private function getEntityArray($array)
    {   
        $entityArray = [];
        foreach ($array as $element) {
            array_push($entityArray, $this->getEntity($element));
        }
        return $entityArray;    
    }
}
