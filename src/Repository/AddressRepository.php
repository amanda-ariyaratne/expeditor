<?php

namespace App\Repository;

use App\Entity\Address;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Address|null find($id, $lockMode = null, $lockVersion = null)
 * @method Address|null findOneBy(array $criteria, array $orderBy = null)
 * @method Address[]    findAll()
 * @method Address[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Address::class);
    }

    public function insert(Address $address)
    {
        $conn = $this->getEntityManager()->getConnection();
        $lastInsertId = $conn->transactional(function($conn) use(&$address) {
            $sql = "INSERT INTO `address` (`house_no`, street,  city) VALUES (:house , :street, :city );";

            $stmt = $conn->prepare($sql);

            $stmt->bindValue('house', $address->getHouseNo());
            $stmt->bindValue('street', $address->getStreet());
            $stmt->bindValue('city', $address->getCity());

            $stmt->execute();
            return $conn->lastInsertId();
        });
        return $lastInsertId;
    }

    public function getByAddressID($id): ?Array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM `address` WHERE id = :id AND deleted_at IS NULL LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$id) {
            $sql = "SELECT * FROM `address` WHERE id = :id AND deleted_at IS NULL LIMIT 1;";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->execute();
            return $stmt->fetch();
        });
        return $this->getEntity($result);
    }

    public function getEntity($params){
        $address = new Address();
        $address->setId($params['id']);
        $address->setHouseNo($params['house_no']);
        $address->setStreet($params['street']);
        $address->setCity($params['city']);
        $address->setCreatedAt(new \DateTime($params['created_at']));
        $address->setUpdatedAt(new \DateTime($params['updated_at']));
        $address->setDeletedAt(new \DateTime($params['deleted_at']));
        return $address;

    }

    public function getAddress_afterINSERT(Address $address){
        $house_no = $address->getHouseNo();
        $street = $address->getStreet();
        $city = $address->getCity();

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT id FROM `address` WHERE house_no = :house_no AND street = :street AND city = :city AND deleted_at IS NULL LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('house_no', $house_no);
        $stmt->bindValue('street', $street);
        $stmt->bindValue('city', $city);
        $stmt->execute();
        $id =  $stmt->fetchAll();

        if($id == null){
            $address_id =  $this->insert($address);
            return $this->getById($address_id);
        }
        else{
            return $this->getById($id[0]["id"]);
        }
    }


    // /**
    //  * @return Address[] Returns an array of Address objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Address
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
