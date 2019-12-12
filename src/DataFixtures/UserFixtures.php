<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('aariyaratne@gmail.com');
        $user->setFirstName('amanda');
        $user->setLastName('ariyaratne');
        $user->setPassword($this->passwordEncoder->encodePassword($user,'123456'));
        $user->setRoles(['admin']);
        
        $manager->persist($user);

        $manager->flush();
    }
}
