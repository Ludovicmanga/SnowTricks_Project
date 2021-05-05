<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\Image;
use App\Entity\Video;
use App\Entity\TrickGroup;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Services\TrickGroupServiceInterface;

class TrickFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 1 ; $i < 12 ; $i++){
            $trick = new Trick;
            $trickGroup = New TrickGroup; 
            $trickGroup->setName('categorie '.$i); 

            $trick
                ->setName('Trick'.$i)
                ->setSlug('Trick'.$i)
                ->setDescription('Le snowboard freestyle (anglicisme) ou la planche acrobatique de neige1 (ou artistique) est la pratique de la planche à neige de figures, apparue dans les années 1980 et héritière du skateboard. 
                 Cette discipline de planche acrobatique consiste pour ses pratiquants à exécuter des figures libres lors de sauts pratiqués à l\'aide de structures diverses utilisées comme tremplin. 
                 Elle a principalement lieu dans des zones spéciales dédiées comme les snowparks ou en milieu urbain. Elle peut aussi être pratiquée en hors-piste (backcountry en anglais). 
                 Cette discipline est considérée comme un sport extrême.
             
                 Le snowboardeur qui pratique le snowboard freestyle est appelé freestyleur (freestyler), ou plus généralement rideur (rider). 
                 Les épreuves de snowboard freestyle disputées en Coupe du monde, aux championnats du monde et aux Jeux olympiques sont le half-pipe, le slopestyle, le big air et le snowboard-cross...')
                 ->setTrickGroup($trickGroup)
                 ->setCoverImageName('img/cover.jpg')
                 ->setCoverImagePath('img/cover.jpg')
                 ->setCreationDate(new \DateTime())
                 ; 
            $manager->persist($trickGroup);
            $manager->persist($trick);
            
            // We add videos     
            $video1 = new Video;
            $video1->setPath('<iframe width="560" height="315" src="https://www.youtube.com/embed/monyw0mnLZg" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'); 
            $video1->setTrick($trick); 
            $manager->persist($video1);  

            $video2 = new Video;
            $video2->setPath('<iframe width="560" height="315" src="https://www.youtube.com/embed/0uGETVnkujA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'); 
            $video2->setTrick($trick); 
            $manager->persist($video2);
            
            $video3 = new Video;
            $video3->setPath('<iframe width="560" height="315" src="https://www.youtube.com/embed/SQyTWk7OxSI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'); 
            $video3->setTrick($trick); 
            $manager->persist($video3);

            // We add images
            $image1 = new Image;
            $image1->setName('img/trick_image1.jpg'); 
            $image1->setPath('img/trick_image1.jpg'); 
            $image1->setTrick($trick); 
            $manager->persist($image1);  

            $image2 = new Image;
            $image2->setName('img/trick_image2.jpg'); 
            $image2->setPath('img/trick_image2.jpg'); 
            $image2->setTrick($trick); 
            $manager->persist($image2);
            
            $image3 = new Image;
            $image3->setName('img/trick_image3.jpg'); 
            $image3->setPath('img/trick_image3.jpg'); 
            $image3->setTrick($trick); 
            $manager->persist($image3); 
        }
        $manager->flush();
    }
}
