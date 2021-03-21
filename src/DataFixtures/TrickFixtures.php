<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TrickFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        for($i = 1 ; $i < 12 ; $i++){
            $user = new Trick;
            $user->setName('Trick'.$i)
                 ->setDescription('Description of the trick')
                 ->setGroupId($i)
                 ->setImage('img/snowfigure.jpg')
                 ->setVideo('img/snowfigure.jpg')
                 ->setCreationDate(new \DateTime()); 
            
            $manager->persist($user);  
                   
        }

        $manager->flush();

        $manager->flush();
    }
}
