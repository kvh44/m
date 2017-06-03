<?php

namespace MiddleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use ApiBundle\Controller\DefaultController as ApiDefaultController;

class DefaultController extends ApiDefaultController
{
    public function indexAction()
    {
        return $this->render('MiddleBundle:Default:index.html.twig');
    }
    
    public function clientIpAction(Request $request)
    {
        return parent::clientIpAction($request);
    }
    
    public function searchEngineStateAction(Request $request)
    {
        return parent::searchEngineStateAction($request);
    }       
    
    public function generateRandomStringAction(Request $request)
    {
        return parent::generateRandomStringAction($request);
    } 
}
