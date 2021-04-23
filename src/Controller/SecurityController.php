<?php

namespace App\Controller;

use App\Entity\User; 
use App\Form\RegistrationType; 
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use App\Services\UserServiceInterface;
use App\Services\MailerServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

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
     * @Entity("user", expr="repository.findOneByActivationToken(token)")
     */
    public function activation(User $user)
    {
        //We launch the activation by using the user service
        $this->userService->activate($user);

        //We send a flash message 
        $this->addFlash('message', 'vous avez bien activé votre compte');

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/forgot-password", name="app_forgotten_password")
     */
    public function forgottenPassword(Request $request)
    {
        $form = $this->createForm(ResetPasswordType::class); 
        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid()){
            $this->userService->createResetToken($form); 
        }

        // we redirect to the page asking for an e-mail 
        return $this->render('security/forgotten_password.html.twig', [
            'emailForm' => $form->createView()
        ]);
    }

    /**
     *@Route("/reset-password/{token}", 
     *    name="app_reset_password"),
     *    @Entity("user", expr="repository.findOneByResetToken(token)")
     */
    public function resetPassword($token, User $user, Request $request)
    {
         // We look for the user having the given token 
         // $user = $this->repository->findOneBy(['reset_token' => $token]); 

        if(!$user){
            $this->addFlash('danger', 'Token inconnu'); 
            return $this->redirectToRoute('security_login'); 
        }

        if($request->isMethod('POST')){
            return $this->userService->resetPassword($token, $request, $user);
        } else {
            return $this->render('security/reset_password.html.twig', [
                'token' => $token
            ]);
        }
    }
}
