<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ApiBundle:Default:index.html.twig');
    }
    
    public function testEmailAction(Request $request)
    {
        $message = \Swift_Message::newInstance()
                ->setTo('bryant.qin@gmail.com')
                ->setFrom('bryant.qin@gmail.com')
                ->setSubject('haha')
                ->setBody('haha')
        ;
        
        $mailLogger = new \Swift_Plugins_Loggers_ArrayLogger();
        $this->get('mailer')->registerPlugin(new \Swift_Plugins_LoggerPlugin($mailLogger));
        $this->get('mailer')->send($message);
        var_dump($mailLogger->dump());
        die;
        return $this->render('ApiBundle:Default:index.html.twig');
    }        
}
