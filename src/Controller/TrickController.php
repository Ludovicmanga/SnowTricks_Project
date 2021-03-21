<?php

namespace App\Controller;

use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrickController extends AbstractController
{
    /**
     * @Route("/trick", name="trick")
     */
    public function displayTrick(): Response
    {
        return $this->render('trick/index.html.twig');
    }

    /**
     * @Route("/trickCreation", name="trickCreation")
     */
    public function createTrick(Request $request, EntityManagerInterface $manager)
     {
        $trick = New Trick; 
        
        $form = $this->createFormBuilder($trick)
                ->add('name')
                ->add('description')
                ->add('GroupId')
                ->add('image')
                ->add('video')
                ->getForm(); 

                $form->handleRequest($request); 

                if($form->isSubmitted() && $form->isValid()) {
                    $trick->setCreationDate(new \DateTime()); 

                    $manager->persist($article); 
                    $manager->flush(); 

                    return $this->redirectToRoute('trick'); 
                }

         return $this->render('trick/creation.html.twig', [
             'formTrickCreation' => $form->createView()
         ]); 
     }
}
