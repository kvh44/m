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
    
    public function updateUserInfoAction(Request $request)
    {
        return $this->container->get('api_massage.UsersService')->updateUserInfo($request);
    }   
    
    public function updateShopInfoAction(Request $request)
    {
        return $this->container->get('api_massage.UsersService')->updateUserInfo($request);
    } 
    
    public function sendNewUserMailAction(Request $request)
    {
        $internal_id = $request->get('internal_id');
        $internal_token = $request->headers->get('internal_token');
        return $this->container->get('api_massage.UsersService')->sendNewUserMail($internal_id, $internal_token);
    }   
    
    public function sendPasswordChangedMailAction(Request $request)
    {
        $internal_id = $request->get('internal_id');
        $internal_token = $request->headers->get('internal_token');
        return $this->container->get('api_massage.UsersService')->sendPasswordChangedMail($internal_id, $internal_token);
    }  
    
    public function sendPasswordForgetMailAction(Request $request)
    {
        $identifier = $request->get('identifier');
        return $this->container->get('api_massage.UsersService')->sendPasswordForgetMail($identifier);
    } 
    
    public function uploadAction(Request $request)
    {
        $is_local = $request->get('is_local') ;
        return $this->container->get('api_massage.PhotoService')->uploadEntry($request, $is_local);
    } 
    
    public function topUserAction(Request $request)
    {
        $internal_id = $request->headers->get('internal_id');
        $internal_token = $request->headers->get('internal_token');
        return $this->container->get('api_massage.UsersService')->topUser($internal_id, $internal_token);
    }
    
    public function deletePhotoAction(Request $request)
    {
        $internal_id_photo = $request->headers->get('internal_id_photo');
        $internal_id = $request->headers->get('internal_id');
        $internal_token = $request->headers->get('internal_token');
        return $this->container->get('api_massage.PhotoService')->deletePhoto($internal_id_photo, $internal_id, $internal_token);
    }        

}
