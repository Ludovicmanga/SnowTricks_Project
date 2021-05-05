<?php

namespace App\Controller;

use DateTime; 
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use App\Entity\Comment;
use App\Form\AppFormFactory;
use App\Services\TrickServiceInterface; 
use App\Services\CommentServiceInterface;
use App\Services\VideoServiceInterface;
use App\Services\ImageServiceInterface;
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
    private $formFactory;

    public function __construct(
        TrickServiceInterface $trickService, 
        AppFormFactory $formFactory) 
        {
        $this->trickService = $trickService; 
        $this->formFactory = $formFactory; 
    }

     /**
     * @Route("show/trick/{id}", 
     *     name="trick_show", 
     *     methods={"HEAD", "GET", "POST"}), 
     *     @Entity("trick", expr="repository.findOneById(id)")
     */
    public function show(Trick $trick, Request $request, CommentServiceInterface $commentService): Response
    {
        $comment = New Comment(); 
        $commentForm = $this->formFactory->create('trick-comment', $comment); 
        
        //We set the number of comments to show on each page and display them
        $limit = 5; 
        $page = (int)$request->query->get("page", 1);
        $paginatedComments = $commentService->getPaginatedComments($page, $limit, $trick); 
        $totalComments = $commentService->getTotalComments($trick); 

        $commentForm->handleRequest($request); 

        if($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setUser($this->getUser());
            $commentService->add($comment, $trick);
            $this->addFlash('message', 'Votre commentaire a bien été ajouté!'); 
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

        if($form->isSubmitted() && $form->isValid()) {
                $this->trickService->add($trick, $form); 
            
                return $this->redirectToRoute('trick_show', [
                    'id' => $trick->getId()
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

        if($form->isSubmitted() && $form->isValid()) {                 
            $this->trickService->update($trick, $form); 
            
            return $this->redirectToRoute('trick_show', [
                'id' => $trick->getId()
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
    public function delete(Trick $trick): RedirectResponse  
     {
        $this->trickService->remove($trick); 
        return $this->redirectToRoute('home'); 
     }

    /**
     * @Route("/loadMoreTricks/{offset}/{quantity}", 
     *     name="load_more_tricks", 
     *     methods={"HEAD", "GET", "POST"}) 
     * 
     * Allow to load the next tricks after the button "load more" is clicked on the home page
     */
    public function loadMore(Request $request, $offset, $quantity = 4) 
    {
      $tricks = $this->trickService->findNextTricks($offset, $quantity); 

        $arrayCollection = array();

        foreach($tricks as $trick) {
            $arrayCollection[] = array(
                'id' => $trick->getId(), 
                'name' => $trick->getName(),
                'description' => $trick->getDescription(), 
                'coverImagePath' => $trick->getCoverImagePath()
            );
        }

        return new JsonResponse($arrayCollection); 
    }
}
