<?php

namespace App\Controller;

use App\Entity\User; 
use App\Form\RegistrationType; 
use App\Form\ResetPasswordType;
use App\Services\UserServiceInterface;
use App\Services\MailerServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
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
        $form->handleRequest($request); 
        if($form->isSubmitted() && $form->isValid()) {
            $this->userService->register($user, $form);
    
            return $this->redirectToRoute('security_login'); 
        }

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
     *     name="activation"), 
     * @Entity("user", 
     *     expr="repository.findOneByActivationToken(token)")
     * 
     * We activate the user account by deleting the activation token
     */
    public function activation(User $user)
    {
        $this->userService->activate($user);
        $this->addFlash('message', 'vous avez bien activé votre compte');

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/forgot-password", name="app_forgotten_password")
     */
    public function sendResetPasswordToken(Request $request)
    {
        $form = $this->createForm(ResetPasswordType::class); 
        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid()){
            $this->userService->createResetToken($form); 
        }

        // we redirect to the form asking for an e-mail 
        return $this->render('security/forgotten_password.html.twig', [
            'emailForm' => $form->createView()
        ]);
    }

    /**
     *@Route("/reset-password/{token}", 
     *    name="app_reset_password"),
     *    @Entity("user", expr="repository.findOneByResetToken(token)")
     * 
     * We identify the user thanks to his token, and set a new password once the form is filled
     */
    public function resetPassword($token, User $user, Request $request)
    {
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

    /**
     * @Route("/test", name="test")
     */

    public function test(){
        $user = new User; 
        $user->setProfilePicturePath('ludo'); 
        dd($user); 
    }
}
