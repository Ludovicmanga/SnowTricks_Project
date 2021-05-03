<?php 

namespace App\Services; 

use App\Entity\Comment; 

interface CommentServiceInterface
{
    public function add(Comment $comment, $trick);

    public function getTotalComments($trick);

    public function getPaginatedComments($page, $limit, $trick);
}
