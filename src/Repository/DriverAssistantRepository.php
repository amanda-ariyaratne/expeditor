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
            $sql = "SELECT * FROM driver_assistant WHERE id = :id AND deleted_at IS NULL LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->execute();
            return $stmt->fetch();
        });
        return $this->getEntity($result);
    }

    public function getAll()
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) {
            $sql = "SELECT * FROM driver_assistant WHERE deleted_at IS NULL;";
            $stmt = $conn->prepare($sql);
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
            $stmt->bindValue('store', $da->getStore()->getId());
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
            $stmt->bindValue('store', $da->getStore()->getId());
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

    private function getEntity($array)
    {
        $da = new DriverAssistant();
        $da->setId($array['id']);
        $da->setNIC($array['NIC']);
        $da->setFirstName($array['first_name']);
        $da->setLastName($array['last_name']);
        $store = $this->getEntityManager() 
                    ->getRepository(Store::class)
                    ->getById($array['store_id']);
        $da->setStore($store);
        $da->setCreatedAt(new \DateTime($array['created_at']));
        $da->setUpdatedAt(new \DateTime($array['updated_at']));
        $da->setDeletedAt(new \DateTime($array['deleted_at']));
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
