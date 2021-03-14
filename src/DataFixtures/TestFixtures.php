<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User; 
use App\Entity\Trick; 
use App\Entity\TrickGroup;
use App\Entity\Message;

class TestFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 1 ; $i < 10 ; $i++){
            $user = new User;
            $user->setEmail('ludovic'.$i.'@gmail.com')
                 ->setPassword(123456)
                 ->setUserName('ludovic'.$i); 
            
            $manager->persist($user);  
                   
        }

        $manager->flush();
    }
}
