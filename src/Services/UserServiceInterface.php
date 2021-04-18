<?php 

namespace App\Services; 

use App\Entity\User; 

interface UserServiceInterface
{
    public function register(User $user, $form); 
}