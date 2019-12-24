<?php

namespace App\Repository;

use App\Entity\TrainTrip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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
}
