<?php 

namespace App\Services; 

use App\Entity\Image;

interface ImageServiceInterface
{
    public function remove(Image $video);
}