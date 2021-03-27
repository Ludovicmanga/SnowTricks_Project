<?php

namespace App\Controller;

use DateTime; 
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use App\Entity\Comment;
use App\Form\TrickType;
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
    //public function show($id, Comment $comment = null, Request $request, EntityManagerInterface $manager): Response
    public function show(Trick $trick, Request $request, EntityManagerInterface $manager): Response
    {
        //we get the trick 
        //$repoTrick = $this->getDoctrine()->getRepository(Trick::class); 
        //$trick = $repoTrick->find($id); 
        //creation of the comment form 
        //if(!$comment) {
            $comment = new Comment(); 
       // }
        
        $commentForm = $this->createForm(CommentType::class, $comment); 

        //handling of the form
        $commentForm->handleRequest($request); 
        if($commentForm->isSubmitted() && $commentForm->isValid()) {
            if(!$comment->getId()) {
                $comment
                    ->setCreationDate(new DateTime())
                    ->setTrick($trick)
                    ->setModificationDate(new DateTime())
                ; 
            //we get the logged in user's id
            $loggedInUserId = $this->getUser()->getId();

            $repoUser = $this->getDoctrine()->getRepository(User::class); 
            $user = $repoUser->find($loggedInUserId); 
            $comment->setUser($user); 
            }

            $manager->persist($comment)
                    ->flush()
            ; 
        }

        return $this->render('trick/show.html.twig', [
            'trick' => $trick, 
            'commentForm' => $commentForm->createView()
        ]);
    }

    /**
     * @Route("/create/trick", name="trick_create")
     */
    public function create(Request $request, EntityManagerInterface $manager)
     {        
         $trick = New Trick; 
        $form = $this->createForm(TrickType::class, $trick); 

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
    public function update(Trick $trick) {
         //$repo = $this->getDoctrine()->getRepository(Trick::class); 
         //$trick = $repo->find($id); 

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

    /**
    * @Route("/delete/video/{id}", name="trick_video_delete")
    */
    public function deleteTrickVideo(Video $video): RedirectResponse  
    {
        $em = $this->getDoctrine()->getManager(); 
        $em->remove($video); 
        $em->flush();
        
        return $this->redirectToRoute('home'); 
    }

    /**
    * @Route("/delete/image/{id}", name="trick_image_delete")
    */
    public function deleteTrickImage(Image $image): RedirectResponse  
    {
        $em = $this->getDoctrine()->getManager(); 
        $em->remove($image); 
        $em->flush();
        
        return $this->redirectToRoute('home'); 
    }


}
