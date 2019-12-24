<?php

namespace App\Repository;

use App\Entity\Cart;
use App\Entity\Product;
use App\Entity\Customer;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Cart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cart[]    findAll()
 * @method Cart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }





    public function insert($cart, $customer_id,  $product_id)
    {
        //check for same product id 
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM cart WHERE customer_id = :customer_id AND product_id=:product_id AND deleted_at IS NULL LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('customer_id', $customer_id);
        $stmt->bindValue('product_id', $product_id);
        $stmt->execute();
        $previous_carts =  $stmt->fetchAll();

        if(count($previous_carts)==0){
            $conn = $this->getEntityManager()->getConnection();
            $sql = "INSERT INTO cart (quantity , `customer_id`, `product_id`) VALUES (:quantity , :customer, :product);";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('quantity', $cart->getQuantity());
            $stmt->bindValue('customer', $customer_id);
            $stmt->bindValue('product', $product_id);
            $stmt->execute();
        }
        else{
            $conn = $this->getEntityManager()->getConnection();
            $sql = "UPDATE cart SET quantity=:quantity WHERE customer_id=:customer_id AND product_id=:product_id AND deleted_at IS NULL";
            $stmt = $conn->prepare($sql);

            $newQuantity = $cart->getQuantity() +  $previous_carts[0]['quantity'];
            $stmt->bindValue('quantity', $newQuantity );
            $stmt->bindValue('customer_id', $customer_id);
            $stmt->bindValue('product_id', $product_id);
            $stmt->execute();
        }


        //decrease product quantity_in_stock
        $rslt = $this->getEntityManager()->getRepository(Product::class)->decreaseQuantity_in_stock($product_id , $cart->getQuantity());

        return $conn->lastInsertId();
    }

    public function getAllByCustomerID($id): ?Array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM cart WHERE customer_id = :id AND deleted_at IS NULL";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function deleteById($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $status = $conn->transactional(function($conn) use(&$id) {
            $cart = $this->getCartByID($id);

            $sql = "UPDATE cart SET deleted_at = now() WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->execute();

            //increase product quantity in stock
            $rslt = $this->getEntityManager()->getRepository(Product::class)->increaseQuantity_in_stock($cart[0]["product_id"], $cart[0]["quantity"]);

            return true;
        });
        return $status;
    }

    public function deleteAllByCustomerId($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $status = $conn->transactional(function($conn) use(&$id) {
            $sql = "UPDATE cart SET deleted_at = now() WHERE customer_id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->execute();
            return true;
        });
        return $status;
    }

    public function getCartByID($id): ?Array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM cart WHERE id = :id AND deleted_at IS NULL";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$id) {
            $sql = "SELECT * FROM cart WHERE id = :id AND deleted_at IS NULL LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->execute();
            return $stmt->fetch();
        });
        return $this->getEntity($result);
    }

    public function getEntity($params){
        $cart = new Cart();
        $cart->setId($params['id']);
        $cart->setQuantity($params['quantity']);
        $cart->setCustomer($this->getEntityManager()->getRepository(Customer::class)->getByID($params['customer_id']));
        $cart->setProduct($this->getEntityManager()->getRepository(Product::class)->getById($params['product_id']));
        $cart->setCreatedAt(new \DateTime($params['created_at']));
        $cart->setUpdatedAt(new \DateTime($params['updated_at']));
        $cart->setDeletedAt(new \DateTime($params['deleted_at']));
        return $cart;

    }


}
