<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function getById($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$id) {
            $sql = "SELECT * FROM product WHERE id = :id AND deleted_at IS NULL LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->execute();
            return $stmt->fetch();
        });
        return $this->getEntity($result);
    }

    public function getEntity($params){
        $product = new Product();
        $product->setId($params['id']);
        $product->setName($params['name']);
        $product->setDescription($params['description']);
        $product->setSize($params['size']);
        $product->setQuantityInStock($params['quantity_in_stock']);
        $product->setWholesalePrice($params['wholesale_price']);
        $product->setRetailPrice($params['retail_price']);
        $product->setRetailLimit($params['retail_limit']);
        $product->setImage($params['image']);
        $product->setCreatedAt(new \DateTime($params['created_at']));
        $product->setUpdatedAt(new \DateTime($params['updated_at']));
        $product->setDeletedAt(new \DateTime($params['deleted_at']));
        return $product;

    }

    public function decreaseQuantity_in_stock($id, $quantity)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "UPDATE product SET quantity_in_stock=:quantity_in_stock WHERE id=:id AND deleted_at IS NULL";
        $stmt = $conn->prepare($sql);

        $quantity_in_stock = $this->getById($id)->getQuantityInStock() - $quantity;
        $stmt->bindValue('quantity_in_stock', $quantity_in_stock);
        $stmt->bindValue('id', $id);
        $stmt->execute();
        return true;
    }

    public function increaseQuantity_in_stock($id, $quantity)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "UPDATE product SET quantity_in_stock=:quantity_in_stock WHERE id=:id AND deleted_at IS NULL";
        $stmt = $conn->prepare($sql);

        $quantity_in_stock = $this->getById($id)->getQuantityInStock() + $quantity;
        $stmt->bindValue('quantity_in_stock', $quantity_in_stock);
        $stmt->bindValue('id', $id);
        $stmt->execute();
        return true;
    }

    
    public function getMostPopularProducts()
    {
        $conn = $this->getEntityManager()->getConnection();
        $results = $conn->transactional(function($conn) {
            $sql = "SELECT * FROM products_with_most_orders_report;";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        });
        return $results;
    }

    public function getAllProducts(): ?Array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM product";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getProductByID($id): ?Array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM product WHERE id = :id AND deleted_at IS NULL";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
