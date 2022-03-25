<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class Mailer 
{
    public function __construct(private MailerInterface $mailer)
    {
        
    }

    public function sendEmail($email, $token, $message, $template)
    {
        $email = (new TemplatedEmail())
            ->from("fred@frederic-caffier.fr")
            ->to(new Address($email))
            ->subject($message)
            ->htmlTemplate($template)
            ->context(
            [
                'token' => $token
            ]
        );
        $this->mailer->send($email);
    }
}