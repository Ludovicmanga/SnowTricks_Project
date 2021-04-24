<?php

namespace App\Controller;

use App\Entity\Image;
use App\Services\ImageServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImageController extends AbstractController
{
    /**
    * @Route("/delete/image/{id}", 
    *     name="trick_image_delete", 
    *     methods={"HEAD", "GET", "POST"})
    */
    public function delete(Image $image, ImageServiceInterface $imageService): Response  
    {
        $imageService->remove($image); 
        
        return $this->redirectToRoute('trick_update', [
            'id' => $image->getTrick()->getId()
        ]); 
    }
}
