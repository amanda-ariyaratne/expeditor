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

    public function getById($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$id) {
            $sql = "SELECT * FROM truck_route_store WHERE id = :id AND deleted_at IS NULL";            
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->bindValue('store', $store);
            $stmt->execute();
            return $stmt->fetch();
        });
        return $this->getEntity($result);
    }   
  
    public function getByStore($store_id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $results = $conn->transactional(function($conn) use(&$store_id) {
            $sql = "SELECT * FROM truck_route_store WHERE store_id=:store AND truck_route_deleted_at IS NULL";            
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('store', $store_id);
            $stmt->execute();
            return $stmt->fetchAll();
        });
        return $this->getEntityArray($results);
    }

    public function getAll(){
        $conn = $this->getEntityManager()->getConnection();
        $results = $conn->transactional(function($conn){
            $sql = "CALL getTruckRoutes(0);";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        });   
        return $this->getEntityArray($results);
    }

    public function getAllByStore($store_id){
        $conn = $this->getEntityManager()->getConnection();
        $results = $conn->transactional(function($conn) use(&$store_id){
            $sql = "CALL getTruckRoutes(:store_id);";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('store_id', $store_id);
            $stmt->execute();
            return $stmt->fetchAll();
        });         
        return $this->getEntityArray($results);
    }

    public function insert($truck_route)
    {
        $conn = $this->getEntityManager()->getConnection();

        $result = $conn->transactional(function($conn) use(&$truck_route) {
            $sql = "INSERT INTO truck_route (name, map, store_id) VALUES (:name, :map, :store);";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('name', $truck_route->getName());
            $stmt->bindValue('map',$truck_route->getMap());
            $stmt->bindValue('max_time_allocation',$truck_route->getMaxTimeAllocation());
            $stmt->bindValue('store', $truck_route->getStore()->getId());
            $stmt->execute();
        });
    }

    public function update($truck_route)
    {   
        $conn = $this->getEntityManager()->getConnection();

        $result = $conn->transactional(function($conn) use(&$truck_route) {
            $sql = "UPDATE truck_route SET name=:_name, map=:map, store_id=:store WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $truck_route->getId());
            $stmt->bindValue('_name', $truck_route->getName());
            $stmt->bindValue('map',$truck_route->getMap());
            $stmt->bindValue('max_time_allocation',$truck_route->getMaxTimeAllocation());
            $stmt->bindValue('store', $truck_route->getStore()->getId());
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
        $truck_route->setId($params['truck_route_id']);
        $truck_route->setName($params['truck_route_name']);
        $truck_route->setMap($params['truck_route_map']);
        $truck_route->setMaxTimeAllocation($params['truck_route_max_time_allocation']);
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

        $truck_route->setStore($store);
        $truck_route->setCreatedAt(new \DateTime($params['truck_route_created_at']));
        $truck_route->setUpdatedAt(new \DateTime($params['truck_route_updated_at']));
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


    public function getTruckRouteById($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$id) {
            $sql = "SELECT * FROM truck_route WHERE id = :id AND deleted_at IS NULL";            
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->execute();
            return $stmt->fetch();
        });
        return $this->getEntity($result);
    } 
}
