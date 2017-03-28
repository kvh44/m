<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

class PublicUserController extends FOSRestController
{
    public function indexAction()
    {
        return $this->render('ApiBundle:Default:index.html.twig');
    }
    
    public function loginAction(Request $request)
    {
        return $request->get('username');
    }        
    
    public function createUserAction(Request $request)
    {
        return $this->container->get('api_massage.UsersService')->createUser($request);
    }
            
}
