<?php 

namespace App\Services; 

interface MailerServiceInterface
{
    public function sendUserActivationToken($user);

    public function sendResetPassword($url);

}