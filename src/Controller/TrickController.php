<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Form\CommentType;
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
    public function showTrick($id, Comment $comment = null, Request $request = null, EntityManagerInterface $manager): Response
    {
        //we get the trick 
        $repoTrick = $this->getDoctrine()->getRepository(Trick::class); 
        $trick = $repoTrick->find($id); 

        //creation of the comment form 
        if(!$comment) {
            $comment = new Comment(); 
        }
        
        $commentForm = $this->createForm(CommentType::class, $comment); 

            //handling of the form
            $commentForm->handleRequest($request); 

            if($commentForm->isSubmitted() && $commentForm->isValid()) {
                if(!$comment->getId()) {
                    $comment->setCreationDate(new \DateTime())
                            ->setTrick($trick)
                            ; 
                //we get the logged in user's id
                $loggedInUserId = $this->getUser()->getId();

                $repoUser = $this->getDoctrine()->getRepository(User::class); 
                $user = $repoUser->find($loggedInUserId); 

                    $comment->setUser($user); 
                }

                $manager->persist($comment); 
                $manager->flush(); 
            }

        return $this->render('trick/show.html.twig', [
            'trick' => $trick, 
            'commentForm' => $commentForm->createView()
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
                ->add('images')
                ->add('videos')
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
