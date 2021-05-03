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
        $hash = $this->encoder->encodePassword($user, $user->getPassword()); 
        $user->setPassword($hash); 
        $user->setActivationToken(md5(uniqid()));

        //We get the profile picture from the form and put it in the database
        $pictures = $form->get('profile_picture')->getData(); 

        foreach($pictures as $picture) {
            // We generate the image file name
            $pictureFile = md5(uniqid()).'.'.$picture->guessExtension();                 
            
            // We copy the file in upload folder
            $picture->move(
                $this->params->get('images_directory'), 
                $pictureFile
            ); 

            $user->setProfilePictureName($pictureFile);
            $user->setProfilePicturePath('uploads/'.$user->getProfilePictureName()); 
            //$user->setProfilePicturePath('); 
        }

        $this->em->persist($user); 
        $this->em->flush();

        $this->session->getFlashBag()->add('message', 'Le compte a bien été créé!');
        $this->mailerService->sendUserActivationToken($user); 
    }

    /**
     * We activate the user account by deleting the token
     */
    public function activate($user){  
        $user->setActivationToken(null); 
        $this->em->persist($user); 
        $this->em->flush(); 
    }

    /**
     * We send a reset token by email to the user
    */
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

        $this->mailerService->resetPassword($url); 
        $this->session->getFlashBag()->add('message', 'un e-mail de confirmation vous a bien été renvoyé'); 
        return new RedirectResponse($this->router->generate('security_login'));
    }

    /**
     * We set the new password after the reset password form was filled 
     */
    public function resetPassword($token, $request, $user)
    {
        // We delete the token and set the new password
        $user->setResetToken(null); 
        $user->setPassword($this->encoder->encodePassword($user, $request->request->get('password')));
        $this->em->persist($user); 
        $this->em->flush();  

        $this->session->getFlashBag()->add('message', 'mot de passe modifié avec succès!');

        return new RedirectResponse($this->router->generate('security_login'));    
    }
}
