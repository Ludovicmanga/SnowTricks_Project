<?php

namespace App\Services; 

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailerService implements MailerServiceInterface
{
    private $mailer; 

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer; 
    }

    public function sendActivationToken($user)
    {
        $message = (new TemplatedEmail())
            ->from('ludovic.mangaj@gmail.com')
            ->to('ludovic.mangaj@gmail.com')
            ->subject('activation de votre compte SnowTricks')
            ->htmltemplate('emails/activation.html.twig')
            ->context([
                'token' => $user->getActivationToken()
            ])
        ;

        //We send the email
        $this->mailer->send($message);
        }
}
