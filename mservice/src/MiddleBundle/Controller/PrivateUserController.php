<?php

namespace MiddleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use ApiBundle\Controller\PrivateUserController as ApiPrivateUserController;

class PrivateUserController extends ApiPrivateUserController
{
    public function updateUserInfoAction(Request $request)
    {
        //$request->headers->set('api_application_key' , $this->container->getParameter('api_application_key'));
        return parent::updateUserInfoAction($request);
    }  
}
