<?php

namespace MiddleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use ApiBundle\Controller\PrivateUserController as ApiPrivateUserController;

class PrivateUserController extends ApiPrivateUserController
{
    public function updateUserInfoAction(Request $request)
    {
        return parent::updateUserInfoAction($request);
    }  
    
    public function updateShopInfoAction(Request $request)
    {
        return parent::updateShopInfoAction($request);
    } 
    
    public function deletePhotoAction(Request $request)
    {
        return parent::deletePhotoAction($request);
    }
    
    public function sendPasswordForgetMailAction(Request $request)
    {
        return parent::sendPasswordForgetMailAction($request);
    }
    
    public function sendPasswordChangedMailAction(Request $request)
    {
        return parent::sendPasswordChangedMailAction($request);
    }  
    
    public function sendNewUserMailAction(Request $request)
    {
        return parent::sendNewUserMailAction($request);
    }   
}
