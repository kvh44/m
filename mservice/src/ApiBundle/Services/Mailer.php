<?php
namespace ApiBundle\Services;

use ApiBundle\Services\UtileService;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

class Mailer
{
    protected $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
    
    public function sendNewUserMail($to, $from, $subject, $user, $type, $option = null)
    {
        $message = \Swift_Message::newInstance()
                ->setTo($to)
                ->setFrom($from)
                ->setSubject($subject)
                ->setContentType($type)
                ->setBody($this->renderView(
                    'Emails/registration.html.twig',
                    array('name' => $user->getUsername())
                ),
            'text/html')
        ;
        $this->mailer->send($message);
    }
}
