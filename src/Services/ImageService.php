<?php 

namespace App\Services; 

use Doctrine\ORM\EntityManagerInterface;

class ImageService implements ImageServiceInterface
{
    private $em; 

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em; 
    }

    public function remove($image)
    {
        $this->em->remove($image); 
        $this->em->flush(); 
    }    
}
