<?php 

namespace App\Services; 

use App\Entity\Trick; 

interface TrickServiceInterface
{
    public function create(Trick $trick); 

    public function update(Trick $trick); 

    public function delete(Trick $trick); 
}