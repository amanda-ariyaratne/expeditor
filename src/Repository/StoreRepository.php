<?php

namespace App\Repository;

use App\Entity\Store;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Store|null find($id, $lockMode = null, $lockVersion = null)
 * @method Store|null findOneBy(array $criteria, array $orderBy = null)
 * @method Store[]    findAll()
 * @method Store[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Store::class);
    }
  
    public function getById($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$id) {
            $sql = "SELECT * FROM store WHERE id = :id AND deleted_at IS NULL LIMIT 1";
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
            $sql = "SELECT * FROM store WHERE deleted_at IS NULL";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        });         
        return $this->getEntityArray($results);
    }

    public function getAllAsArray(){
        $conn = $this->getEntityManager()->getConnection();
        $results = $conn->transactional(function($conn){
            $sql = "SELECT * FROM store WHERE deleted_at IS NULL";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        });         
        return $results;
    }

    public function update($store)
    {   
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$store ) {
            $sql = "UPDATE store SET name=:sname, street=:street, city=:city WHERE id = :id AND deleted_at IS NULL";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $store->getId());
            $stmt->bindValue('sname', $store->getName());
            $stmt->bindValue('street', $store->getStreet());
            $stmt->bindValue('city', $store->getCity());
            $stmt->execute();
        });        
    }

    public function insert(Store $store)
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$store) {
            $sql = "INSERT INTO store (name, street, city) VALUES (:name, :street, :city);";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('name', $store->getName());
            $stmt->bindValue('street',$store->getStreet());
            $stmt->bindValue('city', $store->getCity());
            $stmt->execute();
        });
    }

    public function delete($id)
    {   
        $date = new \DateTime();
        $date = $date->format('Y-m-d H:i:s');
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$id, &$date) {
            $sql = "UPDATE store SET deleted_at=:present WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->bindValue('present', $date);
            $stmt->execute(); 
            return true;  
        });     
        return $result;
    }
  
    public function getEntity($params){
        $store = new Store();
        $store->setId($params['id']);
        $store->setName($params['name']);
        $store->setStreet($params['street']);
        $store->setCity($params['city']);
        $store->setCreatedAt(new \DateTime($params['created_at']));
        $store->setUpdatedAt(new \DateTime($params['updated_at']));
        $store->setDeletedAt(new \DateTime($params['deleted_at']));
        return $store;
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
