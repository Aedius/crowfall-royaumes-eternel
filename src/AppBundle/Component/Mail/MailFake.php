<?php

namespace AppBundle\Component\Mail;


use FOS\UserBundle\Mailer\MailerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

class MailFake extends MailFosAbstract implements MailerInterface, MailInterface
{

    /**
     * @var string domain
     */
    protected $domain;

    /**
     * @var string domain
     */
    protected $from;

    /**
     * MailFake constructor.
     * @param string $domain
     * @param string $from
     * @param RouterInterface $router
     * @param EngineInterface $templating
     * @param array $parameters
     */
    public function __construct(string $domain, string $from, RouterInterface $router, EngineInterface $templating, array $parameters)
    {
        parent::__construct($router, $templating, $parameters);
        $this->domain = $domain;
        $this->from = $from;
    }

    public function mail(string $to, string $subject, string $text): bool
    {

        $mail = new \PHPMailer;
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'mailcatcher';
        $mail->SMTPAuth = false;
        $mail->SMTPSecure = '';
        $mail->Port = 1025;
        $mail->setFrom($this->from, 'serveur de test');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $text;
        if (!$mail->send()) {
            throw new \Exception('failed to send mail to ' . $to);
        }
        return true;
    }
}