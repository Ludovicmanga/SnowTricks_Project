<?php

namespace App\Services; 

class TrickHydration
{
    public function hydrate($trick, $manager){
            $trick->setCreationDate(new \DateTime()); 
            $manager->persist($trick) 
                    ->flush(); 
    }
}