<?php

namespace App\Services; 

use DateTime;
use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;

class TrickCreationService 
{
    private $manager; 

    public function __construct(EntityManagerInterface $manager) {
        $this->manager = $manager; 
    }   

    public function add(Trick $trick) {        
            $trick->setCreationDate(new DateTime());

            $this->manager->persist($trick); 
            $this->manager->flush();
    }
} 