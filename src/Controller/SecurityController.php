<?php

namespace App\Controller;

use App\Entity\User; 
use App\Form\RegistrationType; 
use App\Repository\UserRepository;
use App\Services\UserServiceInterface;
use App\Services\MailerServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SecurityController extends AbstractController
{
    private $userService; 

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route("/registration", 
     *     name="security_registration", 
     *     methods={"HEAD", "GET", "POST"})
     */
    public function registration(Request $request, MailerServiceInterface $mailerService) {
        $user = new User(); 
        $form = $this->createForm(RegistrationType::class, $user); 
        
        // traitement du formulaire
        $form->handleRequest($request); 
        if($form->isSubmitted() && $form->isValid()) {
            // enregistrement de l'utilisateur en passant par le service
            $this->userService->register($user, $form);
            $mailerService->sendActivationToken($user); 
    
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

    /**
     * @Route("/activation/{token}", 
     *     name="activation")
     */
    public function activation($token, UserRepository $userRepo, MailerInterface $mailer){
        //We verify wether a user already has this token
        $user = $userRepo->findOneBy(['activationToken' => $token]); 

        //We launch the activation by using the user service

        //If no user exist with this token 
        if(!$user){
            //404 error
            throw $this->NotFoundException('cet utilisateur n\'existe pas'); 
        }
        
        $this->userService->activate($user);

        //We send a flash message 
        $this->addFlash('message', 'vous avez bien activé votre compte');

        return $this->redirectToRoute('home');
    }

     /**
     * @Route("/testmail/{token}", 
     *     name="testmail")
     */
    public function testMail($token, MailerInterface $mailer)
    {
        $message = (new TemplatedEmail())
            ->from('ludovic.mangaj@gmail.com')
            ->to('ludovic.mangaj@gmail.com')
            ->subject('activation de votre compte SnowTricks')
            ->htmltemplate('emails/activation.html.twig')
            ->context([
                'token' => $token
            ])
        ;
        //We send the email
        $mailer->send($message); 

        //We send a flash message 
        $this->addFlash('message', 'vous avez bien activé votre compte');

        return $this->redirectToRoute('home');

    }

}
