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

    public function getById($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$id) {
            $sql = "SELECT * FROM driver WHERE id = :id AND deleted_at IS NULL LIMIT 1";            
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->execute();
            return $stmt->fetch();
        });
        return $this->getEntity($result);
    }  
  
    public function getAll(){
        $conn = $this->getEntityManager()->getConnection();
        $results = $conn->transactional(function($conn){
            $sql = "SELECT * FROM driver WHERE deleted_at IS NULL";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        });         
        return $this->getEntityArray($results);
    }

    public function getAllAsArray(){
        $conn = $this->getEntityManager()->getConnection();
        $results = $conn->transactional(function($conn){
            $sql = "SELECT * FROM driver WHERE deleted_at IS NULL";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        });         
        return $results;
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

    public function _delete($id)
    {   
        $date = new \DateTime();
        $date = $date->format('Y-m-d H:i:s');
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$id, &$date) {
            $sql = "UPDATE driver SET deleted_at=:present WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->bindValue('present', $date);
            $stmt->execute(); 
            return true;  
        });     
        return $result;
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
        $driver->setId($params['id']);
        $driver->setFirstName($params['first_name']);
        $driver->setLastName($params['last_name']);
        $driver->setNIC($params['NIC']);
        $driver->setLicenseNo($params['license_no']);
        // dd($params['store_id']);
        $store = $this->getEntityManager() 
                    ->getRepository(Store::class)
                    ->getById($params['store_id']);
        
        
        $driver->setStore($store);
        $driver->setCreatedAt(new \DateTime($params['created_at']));
        $driver->setUpdatedAt(new \DateTime($params['updated_at']));

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
