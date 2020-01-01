<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\User;
use App\Entity\Address;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }


    public function getById($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$id) {
            $sql = "SELECT * FROM customer WHERE `user_id` = :id AND deleted_at IS NULL LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->execute();
            return $stmt->fetch();
        });
        return $this->getEntity($result);
    }

    public function getCustomerByID($id): ?Array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM customer WHERE user_id = :id AND deleted_at IS NULL LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function insert(Customer $c)
    {
        $conn = $this->getEntityManager()->getConnection();
        $lastInsertId = $conn->transactional(function($conn) use(&$c) {
            $user = $c->getUser();
            $address = $c->getAddress();

            // $address = new Address();
            // $address->setHouseNo($adrs->first());
            // $address->setStreet($adrs->next());
            // $address->setCity($adrs->next());

            $user_id = $this->getEntityManager()->getRepository(User::class)->insert($user);
            $address_id = $this->getEntityManager()->getRepository(Address::class)->insert($address);

            $sql = "INSERT INTO customer (`user_id`, `address_id`) VALUES (:user, :adrs);";
            $stmt = $conn->prepare($sql);

            $stmt->bindValue('user', $user_id);
            $stmt->bindValue('adrs', $address_id);

            $stmt->execute();
            return $conn->lastInsertId();
        });
        return $lastInsertId;
    }

    public function getEntity($params){
        $customer = new Customer();
        
        $user = $this->getEntityManager()->getRepository(User::class)->getById($params['user_id']);
        $customer->setUser($user);
        $address = $this->getEntityManager()->getRepository(Address::class)->getById($params['address_id']);
        $customer->setAddress($address);
        $customer->setCreatedAt(new \DateTime($params['created_at']));
        $customer->setUpdatedAt(new \DateTime($params['updated_at']));
        $customer->setDeletedAt(new \DateTime($params['deleted_at']));

        return $customer;
    }




    public function getAll(): ?Array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM customer WHERE deleted_at IS NULL";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $customers =  $stmt->fetchAll();

        $customer_arr = array();
        foreach($customers as $c){
            array_push($customer_arr , $this->getEntity($c));
        }
        return $customer_arr;
    }


}
