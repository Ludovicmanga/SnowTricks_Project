<?php

namespace App\Services; 

use DateTime;
use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;

class TrickUpdateService 
{
    private $manager; 
    private $params;

    public function __construct(EntityManagerInterface $manager) {
        $this->manager = $manager; 
    }   

    public function add(Trick $trick) {
                
                $trick->setUpdateDate(new DateTime()); 
                $this->manager->persist($trick); 
                $this->manager->flush();
    }        
            
} 