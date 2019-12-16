<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function getById($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $result = $conn->transactional(function($conn) use(&$id) {
            $sql = "SELECT * FROM user WHERE id = :id AND deleted_at IS NULL LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->execute();
            return $stmt->fetch();
        });
        return $this->getEntity($result);
    }

    public function insert(User $user)
    {
        $conn = $this->getEntityManager()->getConnection();
        $lastInsertId = $conn->transactional(function($conn) use(&$user) {
            $sql = "INSERT INTO user (email, roles, `password`, first_name, last_name) VALUES (:email, :roles, :pass, :fname, :lname);";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('email', $user->getEmail());
            $stmt->bindValue('roles', json_encode($user->getRoles()));
            $stmt->bindValue('pass', $user->getPassword());
            $stmt->bindValue('fname', $user->getFirstName());
            $stmt->bindValue('lname', $user->getLastName());
            $stmt->execute();
            return $conn->lastInsertId();
        });
        return $lastInsertId;
    }

    public function update($user)
    {
        $conn = $this->getEntityManager()->getConnection();
        $status = $conn->transactional(function($conn) use(&$user) {
            $sql = "UPDATE user SET first_name=:fname, last_name=:lname, email=:email WHERE id=:id AND deleted_at IS NULL";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('email', $user->getEmail());
            $stmt->bindValue('fname', $user->getFirstName());
            $stmt->bindValue('lname', $user->getLastName());
            $stmt->bindValue('id', $user->getId());
            $stmt->execute();
            return true;
        });
        return $status;
    }

    public function deleteById($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $status = $conn->transactional(function($conn) use(&$id) {
            $sql = "UPDATE user SET deleted_at = now(), updated_at = now() WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $id);
            $stmt->execute();
            return true;
        });
        return $status;
    }

    private function getEntity($array)
    {
        $user = new User();
        $user->setId($array['id']);
        $user->setEmail($array['email']);
        $user->setRoles(json_decode($array['roles']));
        $user->setPassword($array['password']);
        $user->setFirstName($array['first_name']);
        $user->setLastName($array['last_name']);
        $date = new \DateTime($array['created_at']);
        $user->setCreatedAt(new \DateTime($array['created_at']));
        $user->setUpdatedAt(new \DateTime($array['updated_at']));
        $user->setUpdatedAt(new \DateTime($array['deleted_at']));
        return $user;
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
