<?php

namespace App\Controller;

use DateTime; 
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
//use App\Entity\Upload;
//use App\Services\Test;
use App\Entity\Comment;
// use App\Form\UploadType;
use App\Form\CommentType;
use App\Form\AppFormFactory;
use App\Form\TrickCreateType;
use App\Form\TrickUpdateType;
use App\Repository\CommentRepository;
use App\Services\TrickServiceInterface; 
// use App\Services\TrickUpdateService;
// use App\Services\TrickCreationService;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\CommentServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class TrickController extends AbstractController
{   
    private $trickService; 
    private $em; 
    private $formFactory;

    public function __construct(TrickServiceInterface $trickService, EntityManagerInterface $em, AppFormFactory $formFactory) {
        $this->trickService = $trickService; 
        $this->em = $em; 
        $this->formFactory = $formFactory; 
    }

     /**
     * @Route("show/trick/{trick_id}", 
     *     name="trick_show", 
     *     methods={"HEAD", "GET", "POST"}), 
     *     @Entity("trick", expr="repository.findOneById(trick_id)")
     */
    public function show(Trick $trick, Request $request, CommentServiceInterface $commentService, CommentRepository $commentRepo): Response
    {
        //creation of the form
        $comment = New Comment(); 
        $commentForm = $this->formFactory->create('trick-comment', $comment); 
        
        $limit = 5; 

        // We get the page number
        $page = (int)$request->query->get("page", 1);

        // We get the comments of the page
        $paginatedComments = $commentRepo->getPaginatedComments($page, $limit, $trick); 

        // We get the total number of comments
        $totalComments = $commentRepo->getTotalComments($trick); 

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
            'paginatedComments' => $paginatedComments, 
            'commentForm' => $commentForm->createView(), 
            'totalComments' => $totalComments, 
            'limit' => $limit, 
            'page' => $page
        ]);
    }

    /**
     * @Route("/create/trick", 
     *     name="trick_create", 
     *     methods={"HEAD", "GET", "POST"})
     */
    public function create(Request $request)
     {   
        $trick = New Trick(); 
        $form = $this->formFactory->create('trick-create', $trick); 
        $form->handleRequest($request); 

        //if the form is submitted, we hydrate the trick and send it to the DB
        if($form->isSubmitted() && $form->isValid()) {
                $this->trickService->add($trick, $form); 
            
                return $this->redirectToRoute('trick_show', [
                    'trick_id' => $trick->getId()
            ]); 
        } 
        
        return $this->render('trick/creation.html.twig', [
             'formTrickCreation' => $form->createView()
         ]); 
     }

    /**
    * @Route("/update/trick/{id}", 
    *     name="trick_update", 
    *     methods={"HEAD", "GET", "POST"})
    */
    public function update(Trick $trick, Request $request)
     {  
        $form = $this->formFactory->create('trick-update', $trick); 
        $form->handleRequest($request); 

        //if the form is submitted, we hydrate the trick and send it to the DB by using the service
        if($form->isSubmitted() && $form->isValid()) {                 
                $this->trickService->update($trick); 
                
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
    * @Route("/delete/trick/{id}", 
    *     name="trick_delete", 
    *     methods={"HEAD", "GET", "POST"})
    */
    public function deleteTrick(Trick $trick): RedirectResponse  
     {
        $this->em->remove($trick); 
        $this->em->flush();
        
        return $this->redirectToRoute('home'); 
     }

    /**
    * @Route("/delete/video/{id}", 
    *     name="trick_video_delete", 
    *     methods={"HEAD", "GET", "POST"})
    */
    public function deleteTrickVideo(Video $video): RedirectResponse  
    {
        $this->em->remove($video); 
        $this->em->flush();

        return $this->redirectToRoute('home'); 
    }

    /**
    * @Route("/delete/image/{id}", 
    *     name="trick_image_delete", 
    *     methods={"HEAD", "GET", "POST"})
    */
    public function deleteTrickImage(Image $image): RedirectResponse  
    {
        $this->em->remove($image); 
        $this->em->flush();
        
        return $this->redirectToRoute('trick_update', [
            'id' => $image->getTrick()->getId()
        ]); 
    }
    
    /**
     * @Route("/loadMoreTricks", 
     *     name="load_more_tricks", 
     *     methods={"HEAD", "GET", "POST"}) 
     */
    public function loadMore() {
        $tricks = $this->getDoctrine()->getRepository(Trick::class)->findAll(); 

        $arrayCollection = array();

        foreach($tricks as $trick) {
            $arrayCollection[] = array(
                'name' => $trick->getName(),
                'description' => $trick->getDescription(), 
                'coverImagePath' => $trick->getCoverImagePath()
            );
        }

        return new JsonResponse($arrayCollection);
            }
}