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
        $internal_token = $request->headers->get('internal_token');
        $password = $request->headers->get('password');
        $password1 = $request->headers->get('password1');
        $password2 = $request->headers->get('password2');
        return $this->container->get('api_massage.UsersService')->resetPassword($password, $password1, $password2, $internal_token);
    }

    public function resetEmailAction(Request $request)
    {
        $email = $request->get('email');
        $password = $request->headers->get('password');
        $internal_token = $request->headers->get('internal_token');
        return $this->container->get('api_massage.UsersService')->resetEmail($email, $password, $internal_token);
    }
    
    public function resetTelephoneAction(Request $request)
    {
        $telephone = $request->get('telephone');
        $password = $request->headers->get('password');
        $internal_token = $request->headers->get('internal_token');
        return $this->container->get('api_massage.UsersService')->resetTelephone($telephone, $password, $internal_token);
    }

}
