<?php

namespace App\Services; 

use DateTime; 
use Doctrine\ORM\EntityManagerInterface;

class CommentService
{
    private $manager; 
    // changer en $em
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager; 
    }

    public function add($comment, $trick) {

        if(!$comment->getId()) {
            $comment
                ->setCreationDate(new DateTime())
                ->setTrick($trick)
                ->setModificationDate(new DateTime())
            ; 
            
            $this->manager->persist($comment);
            $this->manager->flush(); 
        } 
    }
}
