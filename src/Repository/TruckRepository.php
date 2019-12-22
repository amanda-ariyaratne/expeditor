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
            $sql = "SELECT * FROM truck WHERE deleted_at IS NULL";
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
            $sql = "SELECT * FROM truck WHERE id = :id AND deleted_at IS NULL LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->execute();
            return $stmt->fetch();
        });
        return $this->getEntity($result);
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

    private function getEntity($array)
    {
        $truck = new Truck();
        $truck->setId($array['id']);
        $truck->setInsuranceNo($array['insurance_no']);
        $truck->setRegistrationNo($array['registration_no']);
        if ($array['store_id']) {
            $store = $this->getEntityManager() 
                        ->getRepository(Store::class)
                        ->getById($array['store_id']);
            $truck->setStore($store);
        }
        $truck->setCreatedAt(new \DateTime($array['created_at']));
        $truck->setUpdatedAt(new \DateTime($array['updated_at']));
        $truck->setDeletedAt(new \DateTime($array['deleted_at']));
        return $truck;
    }

    // /**
    //  * @return Truck[] Returns an array of Truck objects
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
    public function findOneBySomeField($value): ?Truck
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
