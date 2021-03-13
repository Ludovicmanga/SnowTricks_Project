<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(): Response
    {
        return $this->render('users/register.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }
}