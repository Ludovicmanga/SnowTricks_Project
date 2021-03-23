<?php

namespace App\Controller;

use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrickController extends AbstractController
{
    /**
     * @Route("show/trick/{id}", name="trick_show")
     */
    public function showTrick($id): Response
    {
        $repo = $this->getDoctrine()->getRepository(Trick::class); 
        $trick = $repo->find($id); 

        return $this->render('trick/show.html.twig', [
            'trick' => $trick
        ]);
    }

    /**
     * @Route("/create/trick", name="trick_create")
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

                    $manager->persist($trick); 
                    $manager->flush(); 

                    return $this->redirectToRoute('trick'); 
                }

         return $this->render('trick/creation.html.twig', [
             'formTrickCreation' => $form->createView()
         ]); 
     }

    /**
    * @Route("/update/trick/{id}", name="trick_update")
    */
    public function updateTrick($id) {
         $repo = $this->getDoctrine()->getRepository(Trick::class); 
         $trick = $repo->find($id); 

        return $this->render('trick/update.html.twig', [
            'trick' => $trick
        ]); 
    }

    /**
    * @Route("/delete/trick/{id}", name="trick_delete")
    */
    public function deleteTrick(Trick $trick): RedirectResponse  
    {
        $em = $this->getDoctrine()->getManager(); 
        $em->remove($trick); 
        $em->flush();
        
        return $this->redirectToRoute('home'); 
    }
}
