<?php

namespace AppBundle\Component\Mail;

use FOS\UserBundle\Mailer\MailerInterface;
use Mailgun\Mailgun as externalMailGun;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

class MailGun extends MailFosAbstract implements MailerInterface, MailInterface
{
    /**
     * @var string apiKey from mailgun
     */
    protected $key;

    /**
     * @var string domain
     */
    protected $domain;

    /**
     * @var string domain
     */
    protected $from;

    /**
     * MailGun constructor.
     * @param string $key
     * @param string $domain
     * @param string $from
     * @param RouterInterface $router
     * @param EngineInterface $templating
     * @param array $parameters
     */
    public function __construct(string $key, string $domain, string $from, RouterInterface $router, EngineInterface $templating, array $parameters)
    {
        parent::__construct($router, $templating, $parameters);
        $this->key = $key;
        $this->domain = $domain;
        $this->from = $from;
    }

    public function mail(string $to, string $subject, string $text): bool
    {

        $mgClient = externalMailGun::create($this->key);
        $domain = $this->domain;

        $result = $mgClient->messages()->send($domain, [
            'from' => $this->from,
            'to' => $to,
            'subject' => $subject,
            'text' => $text
        ]);

        return true;

    }


}