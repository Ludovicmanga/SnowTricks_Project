<?php

namespace App\Controller;

use App\Entity\User; 
use App\Form\RegistrationType; 
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/registration", 
     *     name="security_registration", 
     *     methods={"HEAD", "GET", "POST"})
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder) {
        $user = new User(); 
        $form = $this->createForm(RegistrationType::class, $user); 
        
        // traitement du formulaire
        $form->handleRequest($request); 
        if($form->isSubmitted() && $form->isValid()) {
            // enregistrement de l'utilisateur
            $hash = $encoder->encodePassword($user, $user->getPassword()); 
            $user->setPassword($hash); 
            $manager->persist($user); 
            $manager->flush();

            return $this->redirectToRoute('security_login'); 
        }

        // affichage du formulaire de la page
        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/connexion", 
     *     name="security_login", 
     *     methods={"HEAD", "GET", "POST"})
     */
    public function login() {
        return $this->render('security/login.html.twig'); 
    }

    /**
     * @Route("/deconnexion", 
     *     name="security_logout", 
     *     methods={"HEAD", "GET", "POST"})
     */
    public function logout() {}
}
