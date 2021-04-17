<?php

namespace App\Services;

use DateTime;
use App\Entity\Image;
use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Repository\TrickRepository; 

class TrickService implements TrickServiceInterface
{
    private $em; 
    private $params;
    private $repository; 

    public function __construct(EntityManagerInterface $em, ParameterBagInterface $params, TrickRepository $repository) {
        $this->em = $em; 
        $this->params = $params;
        $this->repository = $repository; 
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

            foreach ($form->get('videos')->getData() as $video) {
                $video->setTrick($trick);
                $this->em->persist($video);
              }

            //foreach ($form->get('images')->getData() as $adv) {
            // $adv->setAdvert($advert);
            // $em->persist($adv);
            //}

            $trick->setCreationDate(new DateTime());
            $this->em->persist($trick); 
            $this->em->flush();
    }

    public function update(Trick $trick) 
    {   
        $trick->setUpdateDate(new DateTime()); 
        $this->em->persist($trick); 
        $this->em->flush();
    }   

    //The offset and quantity are limited to 50
    public function findNextTricks($offset, $quantity)
    {
        $offset = $offset > 50 ? 50 : $offset ; 
        $quantity = $quantity > 50 ? 50 : $quantity ; 
        $tricks = $this->repository->findNextTricks($offset, $quantity); 
    }
} 