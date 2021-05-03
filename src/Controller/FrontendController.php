<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Services\TrickServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontendController extends AbstractController
{
    /**
     * @Route("/", 
     *     name="home", 
     *     methods={"HEAD", "GET", "POST"})
     */
    public function displayHomePage(TrickServiceInterface $trickService): Response
    {
        $tricks = $this->getDoctrine()->getRepository(Trick::class)->findAll();

        $fourLastTricksOffset = $trickService->findFourLastTricksOffset(); 

        return $this->render('frontend/home.html.twig', [
            'tricks' => $tricks,
            'controller_name' => 'FrontendController', 
            'fourLastTricksOffset' => $fourLastTricksOffset
        ]);
    }
}
