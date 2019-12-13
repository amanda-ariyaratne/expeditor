<?php

namespace App\Repository;

use App\Entity\StoreManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

use App\Entity\User;
use App\Entity\Store;

/**
 * @method StoreManager|null find($id, $lockMode = null, $lockVersion = null)
 * @method StoreManager|null findOneBy(array $criteria, array $orderBy = null)
 * @method StoreManager[]    findAll()
 * @method StoreManager[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StoreManagerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoreManager::class);
    }

    public function getAll()
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) {
            $sql = "SELECT * FROM store_manager WHERE deleted_at IS NULL;";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        });
        return $this->getEntityArray($result);
    }

    public function insert(StoreManager $sm)
    {
        $user = $sm->getUser();
        $user_id = $this->getEntityManager()
                        ->getRepository(User::class)
                        ->insert($user);
        $conn = $this->getEntityManager()->getConnection();
        $lastInsertId = $conn->transactional(function($conn) use(&$sm, &$user_id) {
            $sql = "INSERT INTO store_manager (`user_id`, NIC, service_no, store_id) VALUES (:user, :nic, :service_no, :store);";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('user', $user_id);
            $stmt->bindValue('nic', $sm->getNIC());
            $stmt->bindValue('service_no', $sm->getServiceNo());
            $stmt->bindValue('store', $sm->getStore()->getId());
            $stmt->execute();
            return $conn->lastInsertId();
        });
        return $lastInsertId;
    }

    private function getEntity($array)
    {
        $sm = new StoreManager();
        $user = $this->getEntityManager() 
                    ->getRepository(User::class)
                    ->getById($array['user_id']);
        $sm->setUser($user);
        $sm->setNIC($array['NIC']);
        $sm->setServiceNo($array['service_no']);
        $store = $this->getEntityManager() 
                    ->getRepository(Store::class)
                    ->getById($array['store_id']);
        $sm->setStore($store);
        $sm->setCreatedAt(new \DateTime($array['created_at']));
        $sm->setUpdatedAt(new \DateTime($array['updated_at']));
        $sm->setUpdatedAt(new \DateTime($array['deleted_at']));
        return $sm;
    }

    private function getEntityArray($array)
    {
        $entityArray = [];
        foreach ($array as $element) {
            array_push($entityArray, $this->getEntity($element));
        }
        return $entityArray;
    }

    // /**
    //  * @return StoreManager[] Returns an array of StoreManager objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StoreManager
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
