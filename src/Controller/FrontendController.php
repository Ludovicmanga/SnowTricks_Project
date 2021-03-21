<?php

namespace App\Controller;

use App\Entity\Trick;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontendController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        $trick = $this->getDoctrine()->getRepository(Trick::class)->findAll(); 

        return $this->render('frontend/home.html.twig', [
            'trick' => $trick,
            'controller_name' => 'FrontendController'
        ]);
    }

}
