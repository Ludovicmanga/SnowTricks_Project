<?php 

namespace App\Services; 

use App\Entity\Video;

interface VideoServiceInterface
{
    public function remove(Video $video);
}