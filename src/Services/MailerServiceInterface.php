<?php 

namespace App\Services; 

interface MailerServiceInterface
{
    public function sendActivationToken($user); 

}