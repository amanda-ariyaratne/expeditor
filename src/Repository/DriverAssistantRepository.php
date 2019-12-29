<?php

namespace App\Repository;

use App\Entity\DriverAssistant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

use App\Entity\Store;

/**
 * @method DriverAssistant|null find($id, $lockMode = null, $lockVersion = null)
 * @method DriverAssistant|null findOneBy(array $criteria, array $orderBy = null)
 * @method DriverAssistant[]    findAll()
 * @method DriverAssistant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DriverAssistantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DriverAssistant::class);
    }

    public function getById($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$id) {
            $sql = "CALL getDriverAssistants(:id)";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->execute();
            return $stmt->fetch();
        });
        return $this->getEntity($result);
    }
    public function findDA($stime,$max_time,$_date,$store_id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $results = $conn->transactional(function($conn)use(&$store_id,&$_date,&$max_time,&$stime){
            $sql = 'CALL get_driver_assistants_trips(:stime,:max_time,:_date,:store_id);';
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('store_id', $store_id);
            $stmt->bindValue('_date', $_date,'date');
            $stmt->bindValue('max_time', $max_time);
            $stmt->bindValue('stime', $stime,'time');
            
            $stmt->execute();
            return $stmt->fetchAll();
        });         
        return $this->getEntityArrayforT($results);
    }

    public function getAll()
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) {
            $sql = "CALL getDriverAssistants(0)";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        });
        return $this->getEntityArray($result);
    }

    public function getAllByStore($store_id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$store_id){
            $sql = "CALL getDriverAssistants(:store_id)";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('store_id',$store_id);
            $stmt->execute();
            return $stmt->fetchAll();
        });

        return $this->getEntityArray($result);
    }

    public function insert(DriverAssistant $da)
    {
        $conn = $this->getEntityManager()->getConnection();
        $lastInsertId = $conn->transactional(function($conn) use(&$da) {
            $sql = "INSERT INTO driver_assistant (first_name, last_name, NIC, store_id) VALUES (:fname, :lname, :nic, :store);";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('fname', $da->getFirstName());
            $stmt->bindValue('lname', $da->getLastName());
            $stmt->bindValue('nic', $da->getNIC());
            $stmt->bindValue('store', $da->getStore() == null ? null : $da->getStore()->getId());
            $stmt->execute();
            return $conn->lastInsertId();
        });
        return $lastInsertId;
    }

    public function update(DriverAssistant $da)
    {
        $conn = $this->getEntityManager()->getConnection();
        $status = $conn->transactional(function($conn) use(&$da) {
            $sql = "UPDATE driver_assistant SET NIC=:nic, first_name=:fname, last_name=:lname, store_id=:store WHERE id=:id AND deleted_at IS NULL";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('fname', $da->getFirstName());
            $stmt->bindValue('lname', $da->getLastName());
            $stmt->bindValue('nic', $da->getNIC());
            $stmt->bindValue('store', $da->getStore() == null ? null : $da->getStore()->getId());
            $stmt->bindValue('id', $da->getId());
            $stmt->execute();
            return true;
        });
        return $status;
    }

    public function deleteById($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $status = $conn->transactional(function($conn) use(&$id) {
            $sql = "UPDATE driver_assistant SET deleted_at = now() WHERE id = :id";
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
    private function getEntityArrayforT($array)
    {
        $entityArray = [];
        foreach ($array as $element) {
            array_push($entityArray, $this->getEntityforT($element));
        }
        return $entityArray;
    }

    private function getEntity($params)
    {
        // dd($params);
        $da = new DriverAssistant();
        $da->setId($params['da_id']);
        $da->setFirstName($params['da_first_name']);
        $da->setLastName($params['da_last_name']);
        $da->setNIC($params['da_NIC']);
        
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

        $da->setStore($store);
        $da->setCreatedAt(new \DateTime($params['da_created_at']));
        $da->setUpdatedAt(new \DateTime($params['da_updated_at']));
        $da->setWorkedHours($params['worked_hours']);

        return $da;
    }
    private function getEntityforT($params)
    {
        // dd($params);
        $da = new DriverAssistant();
        $da->setId($params['id']);
        $da->setFirstName($params['first_name']);
        $da->setLastName($params['last_name']);
        $da->setNIC($params['NIC']);
        
        $store=$this->getEntityManager() 
                    ->getRepository(Store::class)
                    ->getById($params['store_id']);
        $da->setStore($store);
        $da->setCreatedAt(new \DateTime($params['created_at']));
        $da->setUpdatedAt(new \DateTime($params['updated_at']));
        

        return $da;
    }

    // /**
    //  * @return DriverAssistant[] Returns an array of DriverAssistant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DriverAssistant
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
