<?php
namespace ApiBundle\Services;

use ApiBundle\Services\UtileService;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

class Mailer
{
    protected $mailer;
    
    protected $template;

    public function __construct($mailer, $template)
    {
        $this->mailer = $mailer;
        $this->template = $template;
    }
    
    public function sendNewUserMail($to, $from, $subject, $user, $type = "text/html", $option = null)
    {
        $message = \Swift_Message::newInstance()
                ->setTo($to)
                ->setFrom($from)
                ->setSubject($subject)
                ->setContentType($type)
                ->setBody($this->template->render(
                    'ApiBundle:Emails:registration.html.twig',
                    array('name' => $user->getUsername())
                ),
            'text/html')
        ;
        $this->mailer->send($message);
    }
}
