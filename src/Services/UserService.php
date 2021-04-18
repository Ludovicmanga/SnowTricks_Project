<?php 

namespace App\Services; 

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class UserService implements UserServiceInterface
{
    private $em; 
    private $encoder; 
    private $params; 

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, ParameterBagInterface $params)
    {
        $this->em = $em; 
        $this->encoder = $encoder; 
        $this->params = $params; 
    }

    public function register($user, $form)
    {
        // enregistrement de l'utilisateur
        $hash = $this->encoder->encodePassword($user, $user->getPassword()); 
        $user->setPassword($hash); 
        $user->setActivationToken(md5(uniqid()));

        //We get the profile picture from the user registration form
        $pictures = $form->get('profile_picture')->getData(); 

        // We get the profile picture

            // we make a loop to get the cover image
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
    }

    public function activate($user){
         
        //If no user exist with this token 
        if(!$user){
            //Erreur 404
            throw $this->createNotFoundException('cet utilisateur n\'existe pas'); 
        }
        
        //We delete the token
        $user->setActivationToken(null); 
        $this->em->persist($user); 
        $this->em->flush(); 
    }

}

