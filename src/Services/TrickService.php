<?php

namespace App\Services; 

use DateTime;
use App\Entity\Image;
use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TrickService 
{
    private $manager; 
    private $params;

    public function __construct(EntityManagerInterface $manager, ParameterBagInterface $params) {
        $this->manager = $manager; 
        $this->params = $params;
    }   

    public function add(Trick $trick, $form) {        
            //We get the images from the trick creation form
            $images = $form->get('images')->getData(); 

            //We get the cover image from the trick creation form
            $coverImages = $form->get('coverImage')->getData(); 

            // we make a loop to get the images
            foreach($images as $image) {
                // We generate the image file name
                $imageFile = md5(uniqid()).'.'.$image->guessExtension();                 
                
                // We copy the file in upload folder
                $image->move(
                    $this->params->get('images_directory'), 
                    $imageFile
                ); 

                // We put the image in the database
                $img = new Image; 
                $img->setName($imageFile);
                $img->setPath('uploads/'.$img->getName()); 
                $trick->addImage($img); 
            }

            // we make a loop to get the cover image
            foreach($coverImages as $coverImage) {
                // We generate the image file name
                $coverImageFile = md5(uniqid()).'.'.$coverImage->guessExtension();                 
                
                // We copy the file in upload folder
                $coverImage->move(
                    $this->params->get('images_directory'), 
                    $coverImageFile
                ); 

                // We put the image in the database
                $trick->setCoverImageName($coverImageFile);
                $trick->setCoverImagePath('uploads/'.$trick->getCoverImageName()); 
                
                // $trick->addImage($img); 
            }

            $trick->setCreationDate(new DateTime());
            $this->manager->persist($trick); 
            $this->manager->flush();
    }

    public function update(Trick $trick) {   
        $trick->setUpdateDate(new DateTime()); 
        $this->manager->persist($trick); 
        $this->manager->flush();
    }   
} 