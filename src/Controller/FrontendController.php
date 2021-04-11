<?php

namespace App\Controller;

use App\Entity\Trick;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontendController extends AbstractController
{
    /**
     * @Route("/", 
     *     name="home", 
     *     methods={"HEAD", "GET", "POST"})
     */
    public function home(): Response
    {
        $tricks = $this->getDoctrine()->getRepository(Trick::class)->findAll(); 

        return $this->render('frontend/home.html.twig', [
            'tricks' => $tricks,
            'controller_name' => 'FrontendController'
        ]);
    }

    /**
     * @Route("/test", 
     *     name="test", 
     *     methods={"HEAD", "GET", "POST"})
     */
    public function test(): Response
    {
        $tricks = $this->getDoctrine()->getRepository(Trick::class)->findAll(); 

        return $this->render('frontend/test.html.twig', [
            'tricks' => $tricks,
            'controller_name' => 'FrontendController'
        ]);
    }
}
