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

    public function sendUserActivationToken($user)
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

        $this->mailer->send($message);
    }

    /**
     * We send the email allowing the user to reset his password
     */
    public function sendResetPassword($url)
    {
        $message = (new TemplatedEmail())
            ->from('ludovic.mangaj@gmail.com')
            ->to('ludovic.mangaj@gmail.com')
            ->subject('réinitialisation de votre mot de passe')
            ->htmltemplate('emails/reset_password.html.twig')
            ->context([
                'url' => $url
            ])
        ;

        $this->mailer->send($message);
    }
}
