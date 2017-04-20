<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use ApiBundle\Util\Qq\qqFileUploader;

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
        $qqfile_name = $request->query->get('qqfile');
        $allowedExtensions = array("jpeg","jpg","bmp","gif","png","png8","png24");
        # define size limit constraint here
        $sizeLimit = 2 * 1024 * 1024;
        # upload
        //$filename = $this->file->getName();
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $uploader->handleUpload($this->getParameter('upload_directory'));
        $photo_path = $this->getParameter('upload_directory').$uploader->getName();
        return array('success' => true, 'origin_path' => $photo_path);
    }        

}
