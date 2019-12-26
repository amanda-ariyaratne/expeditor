<?php

namespace App\Repository;

use App\Entity\TruckRoute;
use App\Entity\Store;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

use App\Entity\StoreManager;

/**
 * @method TruckRoute|null find($id, $lockMode = null, $lockVersion = null)
 * @method TruckRoute|null findOneBy(array $criteria, array $orderBy = null)
 * @method TruckRoute[]    findAll()
 * @method TruckRoute[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TruckRouteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TruckRoute::class);

    }
    public function getById($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$id) {
            $sql = "SELECT * FROM truck_route WHERE id = :id AND deleted_at IS NULL LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->execute();
            return $stmt->fetch();
        });
        return $this->getEntity($result);
    }
    private function getEntity($params){
        $truck_route = new TruckRoute();
        $truck_route->setId($params['id']);
        $truck_route->setName($params['name']);
        
        $truck_route->setMaxTimeAllocation($params['max_time_allocation']);
        
        
        
        return $truck_route;
    }

    public function getByStore($store)
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$store) {
            $sql = "SELECT * FROM truck_route WHERE id = :id AND store_id=:store AND deleted_at IS NULL";            
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->bindValue('store', $store);
            $stmt->execute();
            return $stmt->fetch();
        });
        return $this->getEntity($result);
    }  
  
    public function getAll($store){
        $conn = $this->getEntityManager()->getConnection();
        $results = $conn->transactional(function($conn) use(&$store){
            $sql = "SELECT * FROM truck_route WHERE store_id=:store AND deleted_at IS NULL";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('store', $store);
            // dd($store);
            $stmt->execute();
            return $stmt->fetchAll();
        });         
        return $this->getEntityArray($results);
    }

    public function getAllAsArray($store){
        $conn = $this->getEntityManager()->getConnection();
        $results = $conn->transactional(function($conn) use(&$store){
            $sql = "SELECT * FROM truck_route WHERE deleted_at IS NULL AND store_id:store";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('store', $store);
            $stmt->execute();
            return $stmt->fetchAll();
        });         
        return $results;
    }

    public function insert($truck_route, $store)
    {
        $conn = $this->getEntityManager()->getConnection();

        $result = $conn->transactional(function($conn) use(&$truck_route, &$store) {
            $sql = "INSERT INTO truck_route (name, map, store_id) VALUES (:name, :map, :store);";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('name', $truck_route->getName());
            $stmt->bindValue('map',$truck_route->getMap());
            $stmt->bindValue('store', $store);
            $stmt->execute();
        });
    }

    public function update($truck_route, $store)
    {   
        $conn = $this->getEntityManager()->getConnection();

        $result = $conn->transactional(function($conn) use(&$truck_route, &$store) {
            $sql = "UPDATE truck_route SET name=:_name, map=:map, store_id=:store WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $truck_route->getId());
            $stmt->bindValue('_name', $truck_route->getName());
            $stmt->bindValue('map',$truck_route->getMap());
            $stmt->bindValue('store', $store);
            $stmt->execute();
        });        
    }

    public function delete($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $conn->beginTransaction();
        try{
            $sql = "UPDATE truck_route SET deleted_at=now() WHERE id = :id";
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
        $truck_route = new TruckRoute();
        $truck_route->setId($params['id']);
        $truck_route->setName($params['name']);
        $truck_route->setMap($params['map']);
        $store = $this->getEntityManager() 
                    ->getRepository(Store::class)
                    ->getById($params['store_id']);
        $truck_route->setStore($store);

        
        
        return $truck_route;
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
