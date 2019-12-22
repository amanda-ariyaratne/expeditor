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

    public function getById($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$id) {
            $sql = "SELECT * FROM user_store_manager WHERE id = :id ;";
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
            $sql = "SELECT * FROM user_store_manager;";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        });
        return $this->getEntityArray($result);
    }

    public function insert(StoreManager $sm)
    {
        $conn = $this->getEntityManager()->getConnection();
        $lastInsertId = $conn->transactional(function($conn) use(&$sm) {
            $user = $sm->getUser();
            $user_id = $this->getEntityManager()
                            ->getRepository(User::class)
                            ->insert($user);
            $sql = "INSERT INTO store_manager (`user_id`, NIC, service_no, store_id) VALUES (:user, :nic, :service_no, :store);";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('user', $user_id);
            $stmt->bindValue('nic', $sm->getNIC());
            $stmt->bindValue('service_no', $sm->getServiceNo());
            $stmt->bindValue('store', $sm->getStore() == null ? null : $sm->getStore()->getId());
            $stmt->execute();
            return $conn->lastInsertId();
        });
        return $lastInsertId;
    }

    public function update(StoreManager $sm)
    {
        $conn = $this->getEntityManager()->getConnection();
        $status = $conn->transactional(function($conn) use(&$sm) {
            $user = $sm->getUser();
            $user_id = $this->getEntityManager()
                            ->getRepository(User::class)
                            ->update($user);
            $sql = "UPDATE store_manager SET NIC=:nic, service_no=:service_no, store_id=:store WHERE user_id=:user AND deleted_at IS NULL";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('nic', $sm->getNIC());
            $stmt->bindValue('service_no', $sm->getServiceNo());
            $stmt->bindValue('store', $sm->getStore() == null ? null : $sm->getStore()->getId());
            $stmt->bindValue('user', $sm->getUser()->getId());
            $stmt->execute();
            return true;
        });
        return $status;
    }

    public function deleteById($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $status = $conn->transactional(function($conn) use(&$id) {
            $this->getEntityManager()
                 ->getRepository(User::class)
                 ->deleteById($id);
            $sql = "UPDATE store_manager SET deleted_at = now() WHERE user_id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->execute();
            return true;
        });
        return $status;
    }

    private function getEntity($array)
    {
        $sm = new StoreManager();
        $userArray = [
            'id' => $array['id'],
            'email' => $array['email'],
            'roles' => $array['roles'],
            'password' => $array['password'],
            'first_name' => $array['first_name'],
            'last_name' => $array['last_name'],
            'created_at' => $array['user_created_at'],
            'updated_at' => $array['user_updated_at'],
            'deleted_at' => $array['user_deleted_at']
        ];
        $user = $this->getEntityManager() 
                    ->getRepository(User::class)
                    ->getEntity($userArray);
        $sm->setUser($user);
        $sm->setNIC($array['NIC']);
        $sm->setServiceNo($array['service_no']);
        if ($array['store_id']) {
            $storeArray = [
                'id' => $array['store_id'],
                'name' => $array['name'],
                'street' => $array['street'],
                'city' => $array['city'],
                'created_at' => $array['store_created_at'],
                'updated_at' => $array['store_updated_at'],
                'deleted_at' => $array['store_deleted_at']
            ];
            $store = $this->getEntityManager() 
                        ->getRepository(Store::class)
                        ->getEntity($storeArray);
            $sm->setStore($store);
        }
        $sm->setCreatedAt(new \DateTime($array['sm_created_at']));
        $sm->setUpdatedAt(new \DateTime($array['sm_updated_at']));
        $sm->setUpdatedAt(new \DateTime($array['sm_deleted_at']));
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
