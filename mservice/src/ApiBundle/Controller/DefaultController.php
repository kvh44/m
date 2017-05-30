<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use ApiBundle\Services\UtileService;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ApiBundle:Default:index.html.twig');
    }
    
    public function testEmailAction(Request $request)
    {
        return $this->render('ApiBundle:Emails:registration.html.twig');
    }
    
    public function clientIpAction(Request $request)
    {
        return $this->container->get('api_massage.UtileService')->getClientIp();
    }
    
    public function searchEngineStateAction(Request $request)
    {
        return $this->container->get('api_massage.SearchService')->getSearchEngineAliases();
    }       
    
    public function generateRandomStringAction(Request $request)
    {
        $length = $request->get('length');
        return UtileService::RandomString($length);
    }        
    
}
