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
    public function forgottenPass(EntityManagerInterface $em, Request $request, UserRepository $userRepo, TokenGeneratorInterface $tokenGenerator, MailerServiceInterface $mailerService)
    {
        $form = $this->createForm(ResetPasswordType::class); 
        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData(); 
            $user = $userRepo->findOneByEmail($data['email']); 

            if(!$user){
                $this->addFlash('danger', 'cette adresse n\'existe pas'); 
                return $this->redirectToRoute('security_login'); 
            }

            $token = $tokenGenerator->generateToken(); 

            try {
                $user->setResetToken($token); 
                $em->persist($user); 
                $em->flush(); 
        
            } catch(\Exception $e){
                $this->addFlash('Warning', 'une erreur est survenue'.$e->getMessage()); 
                return $this->redirectToRoute('security_login'); 
            }

            // we generate the password reset URL
            $url = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL); 

            // we send the message
            $mailerService->resetPassword($url); 

            // we write the flash message
            $this->addFlash('message', 'un e-mail de confirmation vous a bien été renvoyé'); 
            return $this->redirectToRoute('security_login'); 
        }

        // we redirect to the page asking for an e-mail 
        return $this->render('security/forgotten_password.html.twig', [
            'emailForm' => $form->createView()
        ]);
    }

    /**
     *@Route("/reset-password/{token}", name="app_reset_password")
     */
    public function resetPassword(
        $token, 
        Request $request, 
        UserPasswordEncoderInterface $passwordEncoder, 
        EntityManagerInterface $em)
    {
        // We look for the user having the given token 
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['reset_token' => $token]); 

        if(!$user){
            $this->addFlash('danger', 'Token inconnu'); 
            return $this->redirectToRoute('security_login'); 
        }

        // If form is sent with post method
        if($request->isMethod('POST')){
            // We delete the token
            $user->setResetToken(null); 

            // We encrypt the password
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $em->persist($user); 
            $em->flush();  

            $this->addFlash('message', 'mot de passe modifié avec succès!');

            return $this->redirectToRoute('security_login'); 
        } else {
            return $this->render('security/reset_password.html.twig', [
                'token' => $token
            ]);
        }
    }

}
