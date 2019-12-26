<?php

namespace App\Repository;

use App\Entity\PurchaseProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PurchaseProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method PurchaseProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method PurchaseProduct[]    findAll()
 * @method PurchaseProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PurchaseProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PurchaseProduct::class);
    }

    public function insert($carts , $purchase_id)
    {
        foreach($carts as $cart){
            $conn = $this->getEntityManager()->getConnection();
            $sql = "INSERT INTO purchase_product (purchase_id,  product_id,  quantity) VALUES (:purchase_id, :product_id, :quantity);";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('purchase_id', $purchase_id);
            $stmt->bindValue('product_id', $cart["product_id"]);
            $stmt->bindValue('quantity', $cart["quantity"]);
            $stmt->execute();
        }
        return true;
    }


}
