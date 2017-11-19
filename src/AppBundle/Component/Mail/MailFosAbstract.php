<?php

namespace AppBundle\Component\Mail;


use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

abstract class MailFosAbstract implements MailInterface
{
    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * MailFosAbstract constructor.
     * @param RouterInterface $router
     * @param EngineInterface $templating
     * @param array $parameters
     */
    public function __construct(RouterInterface $router, EngineInterface $templating, array $parameters)
    {
        $this->router = $router;
        $this->templating = $templating;
        $this->parameters = $parameters;
    }


    /**
     * @param UserInterface $user
     */
    public function sendConfirmationEmailMessage(UserInterface $user)
    {

        $template = $this->parameters['confirmation.template'];
        $url = $this->router->generate('fos_user_registration_confirm', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);
        $rendered = $this->templating->render($template, array(
            'user' => $user,
            'confirmationUrl' => $url
        ));

        $this->sendEmailMessage($rendered, $user->getEmail());
    }

    /**
     * @param $renderedTemplate
     * @param $to
     */
    protected function sendEmailMessage($renderedTemplate, $to)
    {

        $renderedLines = explode("\n", trim($renderedTemplate));
        $subject = $renderedLines[0];
        $body = implode("\n", array_slice($renderedLines, 1));

        $this->mail($to, $subject, $body);

    }

    /**
     * @param UserInterface $user
     */
    public function sendResettingEmailMessage(UserInterface $user)
    {
        $template = $this->parameters['resetting_password.template'];
        $url = $this->router->generate('fos_user_resetting_reset', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);
        $rendered = $this->templating->render($template, array(
            'user' => $user,
            'confirmationUrl' => $url
        ));
        $this->sendEmailMessage($rendered, $user->getEmail());
    }

}