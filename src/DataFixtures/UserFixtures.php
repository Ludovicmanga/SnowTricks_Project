<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 1 ; $i < 10 ; $i++){

            $faker = \Faker\Factory::create('fr_FR'); 
            $user = new User; 
            $user->setUserName($faker->name())
                 ->setEmail($faker->email())
                 ->setPassword('1234556')
                 ->setProfilePictureName('img/user'.$i.'.jpg')
                 ->setProfilePictureName('img/user'.$i.'.jpg')
            ;
        $manager->persist($user); 
        }
        $manager->flush();
    }
}
