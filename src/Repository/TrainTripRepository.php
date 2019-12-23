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
            $sql = "INSERT INTO train_trip (allowed_capacity,start_time) VALUES (:allowed_capacity, :start_time);";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('allowed_capacity', $train_trip->getAllowedCapacity());
            $stmt->bindValue('start_time', $train_trip->getStartTime(),'datetime');
           
            $stmt->execute();
            
            
            
            //return $conn->lastInsertId();
        });
        //return $lastInsertId;
    }

    public function update(TrainTrip $train_trip)
    {
        $conn = $this->getEntityManager()->getConnection();
        $status = $conn->transactional(function($conn) use(&$train_trip) {
            $sql = "UPDATE train_trip SET allowed_capacity =:allowed_capacity,start_time =:start_time WHERE id=:id AND deleted_at IS NULL";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $train_trip->getId());
            $stmt->bindValue('allowed_capacity', $train_trip->getAllowedCapacity());
            $stmt->bindValue('start_time', $train_trip->getStartTime(),'datetime');
           
            $stmt->execute();
            
            
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


    // /**
    //  * @return TrainTrip[] Returns an array of TrainTrip objects
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
    public function findOneBySomeField($value): ?TrainTrip
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
