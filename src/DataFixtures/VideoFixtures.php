<?php

namespace App\DataFixtures;

use App\Entity\Video;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class VideoFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 1 ; $i < 10 ; $i++){
            $video1 = new Video;
            $video1->setPath('https://www.youtube.com/watch?v=t705_V-RDcQ'); 
            
            $manager->persist($video1);  

            $video2 = new Video;
            $video2->setPath('https://www.youtube.com/watch?v=t705_V-RDcQ'); 
            
            $manager->persist($video2);
            
            $video3 = new Video;
            $video3->setPath('https://www.youtube.com/watch?v=t705_V-RDcQ'); 
            
            $manager->persist($video3);
        }

        $manager->flush();
    }
}
