<?php

namespace AppBundle\Component\Mail;


use FOS\UserBundle\Mailer\MailerInterface;

interface MailInterface extends MailerInterface
{
    public function mail(string $to, string $subject, string $text): bool;
}