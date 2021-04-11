<?php

namespace App\Services; 

use DateTime; 
use Doctrine\ORM\EntityManagerInterface;

class CommentService implements CommentServiceInterface
{
    private $em; 
    // changer en $em
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em; 
    }

    public function add($comment, $trick) {

            $comment
                ->setCreationDate(new DateTime())
                ->setTrick($trick)
                ->setModificationDate(new DateTime())
            ; 
            
            $this->em->persist($comment);
            $this->em->flush(); 
    }
}
