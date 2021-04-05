<?php

namespace App\Controller;

use DateTime; 
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use App\Entity\Upload;
use App\Services\Test;
use App\Entity\Comment;
use App\Form\TrickType;
use App\Form\UploadType;
use App\Form\CommentType;
use App\Form\TrickUpdateType;
use App\Services\MyFormFactory;
use App\Services\CommentService;
use App\Services\FormFactoryService;
use App\Services\TrickUpdateService;
use App\Services\TrickCreationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class TrickController extends AbstractController
{   
     /**
     * @Route("show/trick/{trick_id}", name="trick_show", methods={"HEAD", "GET", "POST"})
     * @Entity("trick", expr="repository.findOneById(trick_id)")
     */
    public function show(Trick $trick, Request $request, CommentService $commentService): Response
    {
        //creation of the form
        $comment = New Comment(); 
        $commentForm = $this->createForm(CommentType::class, $comment); 

        //handling of the form
        $commentForm->handleRequest($request); 
        if($commentForm->isSubmitted() && $commentForm->isValid()) {

            //We assign the user id to the comment author
            $comment->setUser($this->getUser());

            //We add the comment content and the trick to the trick comment
            $commentService->add($comment, $trick);
        }
        
        return $this->render('trick/show.html.twig', [
            'trick' => $trick, 
            'commentForm' => $commentForm->createView()
        ]);
    }

    /**
     * @Route("/create/trick", name="trick_create")
     */
    public function create(Request $request, EntityManagerInterface $manager, TrickCreationService $trickCreationService)
     {  
        $trick = New Trick(); 
        $form = $this->createForm(TrickType::class, $trick); 
        $form->handleRequest($request); 

        //if the form is submitted, we hydrate the trick and send it to the DB
        if($form->isSubmitted() && $form->isValid()) {
                $trickCreationService->add($trick, $form); 
            
                return $this->redirectToRoute('trick_show', [
                    'trick_id' => $trick->getId()
            ]); 
        } 
        
        return $this->render('trick/creation.html.twig', [
             'formTrickCreation' => $form->createView()
         ]); 
     }

    /**
    * @Route("/update/trick/{id}", name="trick_update")
    */
    public function update(Trick $trick, Request $request, EntityManagerInterface $manager, TrickUpdateService $trickUpdateService)
     {  
        $form = $this->createForm(TrickUpdateType::class, $trick); 
        $form->handleRequest($request); 

        //if the form is submitted, we hydrate the trick and send it to the DB by using the service
        if($form->isSubmitted() && $form->isValid()) {                 
                $trickUpdateService->add($trick); 
                
                //We then return the updated trick
                return $this->redirectToRoute('trick_show', [
                            'trick_id' => $trick->getId()
                ]); 
        } 
        
        return $this->render('trick/update.html.twig', [
             'formTrickCreation' => $form->createView(), 
             'trick' => $trick, 
             'form' => $form->createView()
        ]); 
     }

    /**
    * @Route("/delete/trick/{id}", name="trick_delete")
    */
    public function deleteTrick(Trick $trick, EntityManagerInterface $manager): RedirectResponse  
     {
        $manager->remove($trick); 
        $manager->flush();
        
        return $this->redirectToRoute('home'); 
     }

    /**
    * @Route("/delete/video/{id}", name="trick_video_delete")
    */
    public function deleteTrickVideo(Video $video, EntityManagerInterface $manager): RedirectResponse  
    {
        $manager->remove($video); 
        $manager->flush();

        return $this->redirectToRoute('home'); 
    }

    /**
    * @Route("/delete/image/{id}", name="trick_image_delete")
    */
    public function deleteTrickImage(Image $image, EntityManagerInterface $manager): RedirectResponse  
    {
        $manager->remove($image); 
        $manager->flush();
        
        return $this->redirectToRoute('trick_update', [
            'id' => $image->getTrick()->getId()
        ]); 
    }

    /**
     * @Route("/test", name="test")
     */
    public function test(Request $request) {

        $upload = new Upload(); 
        $form = $this->createForm(UploadType::class, $upload); 

        $form->handleRequest($request); 
        if($form->isSubmitted() && $form->isValid()) {
            $file = $upload->getName(); 
            $fileName = md5(uniqid()).'.'.$file->guessExtension(); 
            $file->move($this->getParameter('images_directory'), $fileName); 
            $upload->setName($fileName);

            return $this->redirectToRoute('home'); 
        }

        return $this->render('trick/test.html.twig', [
            'form' => $form->createView(), 
        ]); 
    }
}
