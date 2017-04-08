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
    
    public function sendNewUserMail($to, $from, $subject)
    {
        $message = \Swift_Message::newInstance()
                ->setTo($to)
                ->setFrom($from)
                ->setSubject($subject)
                ->setBody('haha')
        ;
        
        //$mailLogger = new \Swift_Plugins_Loggers_ArrayLogger();
        //$this->mailer->registerPlugin(new \Swift_Plugins_LoggerPlugin($mailLogger));
        $this->mailer->send($message);
        //var_dump($mailLogger->dump());
        //die;
    }
}
