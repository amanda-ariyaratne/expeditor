<?php

namespace App\Repository;

use App\Entity\ChainManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

use App\Entity\User;
//use App\Entity\Store;

/**
 * @method ChainManager|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChainManager|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChainManager[]    findAll()
 * @method ChainManager[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChainManagerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChainManager::class);
    }

    public function getById($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$id) {
            $sql = "SELECT * FROM chain_manager WHERE user_id = :id AND deleted_at IS NULL LIMIT 1";
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
            $sql = "SELECT * FROM chain_manager WHERE deleted_at IS NULL;";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        });
        return $this->getEntityArray($result);
    }

    public function insert(ChainManager $sm)
    {
        $conn = $this->getEntityManager()->getConnection();
        $lastInsertId = $conn->transactional(function($conn) use(&$sm) {
            $user = $sm->getUser();
            $user_id = $this->getEntityManager()
                            ->getRepository(User::class)
                            ->insert($user);
            $sql = "INSERT INTO chain_manager (`user_id`, NIC, service_no) VALUES (:user, :nic, :service_no);";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('user', $user_id);
            $stmt->bindValue('nic', $sm->getNIC());
            $stmt->bindValue('service_no', $sm->getServiceNo());
           
            $stmt->execute();
            return $conn->lastInsertId();
        });
        return $lastInsertId;
    }

    public function update(ChainManager $sm)
    {
        $conn = $this->getEntityManager()->getConnection();
        $status = $conn->transactional(function($conn) use(&$sm) {
            $user = $sm->getUser();
            $user_id = $this->getEntityManager()
                            ->getRepository(User::class)
                            ->update($user);
            $sql = "UPDATE chain_manager SET NIC=:nic, service_no=:service_no WHERE user_id=:user AND deleted_at IS NULL";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('nic', $sm->getNIC());
            $stmt->bindValue('service_no', $sm->getServiceNo());
            
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
            $sql = "UPDATE chain_manager SET deleted_at = now() WHERE user_id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->execute();
            return true;
        });
        return $status;
    }

    private function getEntity($array)
    {
        $sm = new ChainManager();
        $user = $this->getEntityManager() 
                    ->getRepository(User::class)
                    ->getById($array['user_id']);
        $sm->setUser($user);
        $sm->setNIC($array['NIC']);
        $sm->setServiceNo($array['service_no']);
        
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
