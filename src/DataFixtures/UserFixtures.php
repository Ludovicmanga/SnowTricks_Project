<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder; 
    }
    
    public function load(
        ObjectManager $manager)
    {
        for($i = 1 ; $i < 10 ; $i++){

            $faker = \Faker\Factory::create('fr_FR'); 
            $user = new User; 
            $hash = $this->encoder->encodePassword($user, '12345678');
            $user->setUserName($faker->name())
                 ->setEmail($faker->email())
                 ->setPassword($hash)
                 ->setProfilePictureName('img/user'.$i.'.jpg')
                 ->setProfilePicturePath('img/user'.$i.'.jpg')
            ;
        $manager->persist($user); 
        }
        $manager->flush();
    }
}
