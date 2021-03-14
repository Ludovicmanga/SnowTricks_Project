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

    /**
     * @Route("/login", name="login")
     */
    public function login(): Response
    {
        return $this->render('users/login.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }

    /**
     * @Route("/forgottenPassword", name="forgottenPassword")
     */
    public function forgottenPassword(): Response
    {
        return $this->render('users/forgottenPassword.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }

    /**
     * @Route("/resetPassword", name="resetPassword")
     */
    public function resetPassword(): Response
    {
        return $this->render('users/resetPassword.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }
}
