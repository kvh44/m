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
    
    public function sendNewUserMail($to, $from, $cc, $subject, $data, $attachement = null)
    {
        $username = $data['username'];
        $telephone = $data['telephone'];
        $email = $data['email'];
        $indication = $data['indication'];
        $created = $data['created'];
        $message = \Swift_Message::newInstance()
                ->setTo($to)
                ->setCc($cc)
                ->setFrom($from)
                ->setSubject($subject)
                ->setBody(
                        $this->template->render(
                            'ApiBundle:Emails:registration.html.twig',
                                array('username' => $username, 
                                      'telephone' => $telephone,
                                      'email' => $email,
                                      'indication' => $indication,
                                      'created' => $created
                                )
                        ),
                        'text/html'
                )
        ;
        
        // debug swiftmailer
        //$mailLogger = new \Swift_Plugins_Loggers_ArrayLogger();
        //$this->mailer->registerPlugin(new \Swift_Plugins_LoggerPlugin($mailLogger));
        $this->mailer->send($message);
    }
}
