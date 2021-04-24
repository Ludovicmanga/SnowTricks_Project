<?php

namespace App\Controller;

use App\Services\VideoServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VideoController extends AbstractController
{
    /**
    * @Route("/delete/video/{id}", 
    *     name="trick_video_delete", 
    *     methods={"HEAD", "GET", "POST"})
    */
    public function delete(Video $video, VideoServiceInterface $videoService): Response  
    {
        $videoService->remove($video); 
        return $this->redirectToRoute('trick_update', [
            'id' => $video->getTrick()->getId()
        ]); 
    }
}
