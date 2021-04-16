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
            $trick = new Trick;
            $trick
                ->setName('Trick'.$i)
                ->setDescription('Le snowboard freestyle (anglicisme) ou la planche acrobatique de neige1 (ou artistique) est la pratique de la planche à neige de figures, apparue dans les années 1980 et héritière du skateboard. 
                 Cette discipline de planche acrobatique consiste pour ses pratiquants à exécuter des figures libres lors de sauts pratiqués à l\'aide de structures diverses utilisées comme tremplin. 
                 Elle a principalement lieu dans des zones spéciales dédiées comme les snowparks ou en milieu urbain. Elle peut aussi être pratiquée en hors-piste (backcountry en anglais). 
                 Cette discipline est considérée comme un sport extrême.
             
                 Le snowboardeur qui pratique le snowboard freestyle est appelé freestyleur (freestyler), ou plus généralement rideur (rider). 
                 Les épreuves de snowboard freestyle disputées en Coupe du monde, aux championnats du monde et aux Jeux olympiques sont le half-pipe, le slopestyle, le big air et le snowboard-cross...')
                 ->setGroupId($i)
                 ->setCoverImagePath('img/cover.jpg')
                 ->setCreationDate(new \DateTime()); 
            
            $manager->persist($trick);  
                   
        }
        $manager->flush();
    }
}
