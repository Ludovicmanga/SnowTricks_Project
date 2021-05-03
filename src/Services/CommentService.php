<?php

namespace App\Services; 

use DateTime; 
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;

class CommentService implements CommentServiceInterface
{
    private $em;
    private $repository; 

    public function __construct(
        EntityManagerInterface $em,
        CommentRepository $repository)
    {
        $this->em = $em;
        $this->repository = $repository; 
    }

    public function add($comment, $trick) {
        $comment
            ->setCreationDate(new DateTime())
            ->setTrick($trick)
            ->setModificationDate(new DateTime())
        ; 

        $this->em->persist($comment);
        $this->em->flush(); 
    }

    public function getTotalComments($trick){
        return $this->repository->getTotalComments($trick); 
    }

    public function getPaginatedComments($page, $limit, $trick){
        return $this->repository->getPaginatedComments($page, $limit, $trick); 
    }

}
