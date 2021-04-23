<?php 

namespace App\Services; 

use Twig\Environment;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UserService implements UserServiceInterface
{
    private $em; 
    private $encoder; 
    private $params; 
    private $mailerService; 
    private $router; 
    private $session;
    private $urlGenerator; 
    private $tokenGenerator;  
    private $templating; 

    public function __construct(
        EntityManagerInterface $em, 
        UserPasswordEncoderInterface $encoder, 
        ParameterBagInterface $params, 
        MailerServiceInterface $mailerService, 
        UserRepository $userRepository, 
        TokenGeneratorInterface $tokenGenerator, 
        RouterInterface $router, 
        UrlGeneratorInterface $urlGenerator,
        SessionInterface $session, 
        Environment $templating)
    {
        $this->em = $em; 
        $this->encoder = $encoder; 
        $this->params = $params; 
        $this->mailerService = $mailerService;
        $this->repository = $userRepository; 
        $this->session = $session;
        $this->urlGenerator = $urlGenerator; 
        $this->tokenGenerator = $tokenGenerator;
        $this->router = $router; 
        $this->templating = $templating; 
    }

    public function register($user, $form)
    {
        // enregistrement de l'utilisateur
        $hash = $this->encoder->encodePassword($user, $user->getPassword()); 
        $user->setPassword($hash); 
        $user->setActivationToken(md5(uniqid()));

        // We get the profile picture from the user registration form
        $pictures = $form->get('profile_picture')->getData(); 

        // We make a loop to get the profile picture
        foreach($pictures as $picture) {
            // We generate the image file name
            $pictureFile = md5(uniqid()).'.'.$picture->guessExtension();                 
            
            // We copy the file in upload folder
            $picture->move(
                $this->params->get('images_directory'), 
                $pictureFile
            ); 

            // We put the image in the database
            $user->setProfilePictureName($pictureFile);
            $user->setProfilePicturePath('uploads/'.$user->getProfilePictureName()); 
        }

        $this->em->persist($user); 
        $this->em->flush();

        // We create a flash message to indicate the account creation worked 
        $this->session->getFlashBag()->add('message', 'Le compte a bien été créé!');
        
        //After the user is created, we send it an activation link by e-mail
        $this->mailerService->sendActivationToken($user);
    }

    public function activate($user){
        
        //We delete the token
        $user->setActivationToken(null); 
        $this->em->persist($user); 
        $this->em->flush(); 
    }

    public function createResetToken($form)
    {
        // We identify the user thanks to the form input
        $data = $form->getData(); 
        $user = $this->repository->findOneByEmail($data['email']); 

        if(!$user){
            $this->session->getFlashBag()->add('message', 'l\'utilisateur n\'existe pas!');
            return new RedirectResponse($this->router->generate('security_login'));
        }

        $token = $this->tokenGenerator->generateToken(); 

        try {
            $user->setResetToken($token); 
            $this->em->persist($user); 
            $this->em->flush(); 
    
        } catch(\Exception $e){
            $this->session->getFlashBag('message', 'une erreur est survenue'.$e->getMessage()); 
            return new RedirectResponse($this->router->generate('home'));
        }

        // we generate the password reset URL
        $url = $this->urlGenerator->generate('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL); 

        // we send the message
        $this->mailerService->resetPassword($url); 

        // we write the flash message
        $this->session->getFlashBag()->add('message', 'un e-mail de confirmation vous a bien été renvoyé'); 
        return new RedirectResponse($this->router->generate('security_login'));
    }

    public function resetPassword($token, $request, $user)
    {
            // We delete the token
            
            $user->setResetToken(null); 
            
            // We encrypt the password
            $user->setPassword($this->encoder->encodePassword($user, $request->request->get('password')));
            $this->em->persist($user); 
            $this->em->flush();  

            $this->session->getFlashBag()->add('message', 'mot de passe modifié avec succès!');

            return new RedirectResponse($this->router->generate('security_login'));    
    }

}

