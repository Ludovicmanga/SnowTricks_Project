<?php 

namespace App\Services; 

use Doctrine\ORM\EntityManagerInterface;

class VideoService implements VideoServiceInterface
{
    private $em; 

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em; 
    }

    public function remove($video)
    {
        $this->em->remove($video); 
        $this->em->flush(); 
    }
}

