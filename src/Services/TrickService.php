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

    public function __construct(
        EntityManagerInterface $em, 
        ParameterBagInterface $params, 
        TrickRepository $repository) 
    {
        $this->em = $em; 
        $this->params = $params;
        $this->repository = $repository; 
    }   

    public function add(Trick $trick, $form) {        
        $images = $form->get('images')->getData(); 

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

        $coverImage = $form->get('coverImage')->getData(); 
        
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

        foreach ($form->get('videos')->getData() as $video) {
            $video->setTrick($trick);
            $this->em->persist($video);
            }

        $trick->setCreationDate(new DateTime());
        $this->em->persist($trick); 
        $this->em->flush();
    }

    public function update(Trick $trick, $form) 
    {   
         $images = $form->get('images')->getData(); 

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

         // We allow the adding of new videos
         foreach ($form->get('videos')->getData() as $video) {
            $video->setTrick($trick);
            $this->em->persist($video);
         }

        $trick->setUpdateDate(new DateTime()); 
        $this->em->persist($trick); 
        $this->em->flush();
    }   

    /**
     * Used with the "load more button" in the home page, allows to get new tricks dynamically
     */
    public function findNextTricks($offset, $quantity)
    {
        // We make sure the number of tricks loaded isn't too big
        $offset = $offset > 50 ? 50 : $offset ; 
        $quantity = $quantity > 50 ? 50 : $quantity ; 
        
        return $this->repository->findNextTricks($offset, $quantity);   
    }

    public function findFourLastTricksOffset(){
        $allTricks = $this->repository->getTotalTricks();
        return $allTricks - 4;  
    }

    public function remove($trick)
    {
        $this->em->remove($trick); 
        $this->em->flush(); 
    }
} 
