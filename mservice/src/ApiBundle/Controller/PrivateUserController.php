<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

class PrivateUserController extends FOSRestController
{
    public function resetPasswordAction(Request $request)
    {
        return $this->container->get('api_massage.UsersService')->resetPassword($request);
    }

    public function resetEmailAction(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');
        $internal_token = $request->headers->get('internal_token');
        return $this->container->get('api_massage.UsersService')->resetEmail($email, $password, $internal_token);
    }

}
