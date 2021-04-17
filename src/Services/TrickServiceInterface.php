<?php 

namespace App\Services; 

use App\Entity\Trick; 

interface TrickServiceInterface
{
    public function add(Trick $trick, $form); 

    public function update(Trick $trick); 

    public function findNextTricks($offset, $quantity);

}