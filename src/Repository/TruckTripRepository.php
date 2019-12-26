<?php

namespace App\Repository;

use App\Entity\TruckRoute;
use App\Entity\Driver;
use App\Entity\DriverAssistant;
use App\Entity\Truck;
use App\Entity\TruckTrip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TruckTrip|null find($id, $lockMode = null, $lockVersion = null)
 * @method TruckTrip|null findOneBy(array $criteria, array $orderBy = null)
 * @method TruckTrip[]    findAll()
 * @method TruckTrip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TruckTripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TruckTrip::class);
    }

    public function getAll()
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) {
            $sql = "SELECT * FROM truck_trip WHERE deleted_at IS NULL;";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        });
        return $this->getEntityArray($result);
    }
    public function findD($time,$truckr)
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) {
            $sql = "SELECT * FROM truck_trip WHERE deleted_at IS NULL;";
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
            $sql = "SELECT * FROM truck_trip WHERE id = :id AND deleted_at IS NULL LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->execute();
            return $stmt->fetch();
        });
        return $this->getEntity($result);
    }

    public function insert(TruckTrip $truckt)
    {
        $conn = $this->getEntityManager()->getConnection();
        $lastInsertId = $conn->transactional(function($conn) use(&$truckt) {
            $sql = "INSERT INTO truck_trip (truck_id,driver_id,driver_assistant_id,truck_route_id,start_time) VALUES (:truck,:driver,:driver_assistant,:truck_route,:start_time);";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('truck', $truckt->getTruck()->getId());
            $stmt->bindValue('driver', $truckt->getDriver()->getId());
            $stmt->bindValue('driver_assistant', $truckt->getDriverAssistant()->getId());
            $stmt->bindValue('truck_route', $truckt->getTruckRoute()->getId());
            
            $stmt->bindValue('start_time', $truckt->getStartTime(),'datetime');
            
            $stmt->execute();
            return $conn->lastInsertId();
        });
        return $lastInsertId;
    }

    public function update(TruckTrip $truckt)
    {
        $conn = $this->getEntityManager()->getConnection();
        $status = $conn->transactional(function($conn) use(&$truckt) {
            $sql = "UPDATE truck_trip SET truck_id=:truck, driver_id=:driver,driver_assistant_id=:driver_assistant, truck_route_id=:truck_route, start_time=:start_time WHERE id=:id AND deleted_at IS NULL";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('truck', $truckt->getTruck()->getId());
            $stmt->bindValue('driver', $truckt->getDriver()->getId());
            $stmt->bindValue('driver_assistant', $truckt->getDriverAssistant()->getId());
            $stmt->bindValue('truck_route', $truckt->getTruckRoute()->getId());
            //$stmt->bindValue('date', $truckt->getDate(),'date');
            $stmt->bindValue('start_time', $truckt->getStartTime(),'datetime');
            $stmt->bindValue('id', $truckt->getId());
            
            
            $stmt->execute();
            return true;
        });
        return $status;
    }

    public function delete($id)
    {   
        $date = new \DateTime();
        $date = $date->format('Y-m-d H:i:s');
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$id, &$date) {
            $sql = "UPDATE truck_trip SET deleted_at=:present WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->bindValue('present', $date);
            $stmt->execute(); 
            return true;  
        });     
        return $result;
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
        $truckt = new TruckTrip();
        $truckt->setId($array['id']);
        $truck = $this->getEntityManager() 
                    ->getRepository(Truck::class)
                    ->getById($array['truck_id']);
        $truckt->setTruck($truck);
        $driver = $this->getEntityManager() 
                    ->getRepository(Driver::class)
                    ->getById($array['driver_id']);
        $truckt->setDriver($driver);
        $drivera = $this->getEntityManager() 
                    ->getRepository(DriverAssistant::class)
                    ->getById($array['driver_assistant_id']);
        $truckt->setDriverAssistant($drivera);
        $truckr = $this->getEntityManager() 
                    ->getRepository(TruckRoute::class)
                    ->getById($array['truck_route_id']);
        $truckt->setTruckRoute($truckr);
        $truckt->setDate(new \DateTime($array['date']));
        $truckt->setStartTime(new \DateTime($array['start_time']));
        
        
        
        $truckt->setCreatedAt(new \DateTime($array['created_at']));
        $truckt->setUpdatedAt(new \DateTime($array['updated_at']));
        $truckt->setDeletedAt(new \DateTime($array['deleted_at']));
        return $truckt;
    }


    // /**
    //  * @return TruckTrip[] Returns an array of TruckTrip objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TruckTrip
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
