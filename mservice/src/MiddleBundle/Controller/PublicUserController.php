<?php

namespace MiddleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use ApiBundle\Controller\PublicUserController as ApiPublicUserController;

class PublicUserController extends ApiPublicUserController
{
    public function sendPasswordForgetMailAction(Request $request)
    {
        return parent::sendPasswordForgetMailAction($request);
    } 
    
    public function loginAction(Request $request)
    {
        return parent::loginAction($request);
    }
    
    public function createUserAction(Request $request)
    {
        return parent::createUserAction($request);
    }
    
    public function getSingleUserPageAction(Request $request)
    {
        return parent::getSingleUserPageAction($request);
    }
    
    public function getSingleShopPageAction(Request $request)
    {
        return parent::getSingleShopPageAction($request);
    }
    
    public function removeSingleUserPageCacheAction(Request $request)
    {
        return parent::removeSingleUserPageCacheAction($request);
    }
    
    public function getSingleUserPhotosAction(Request $request)
    {
        return parent::getSingleUserPhotosAction($request);
    }
    
    public function getProfilePhotoAction(Request $request)
    {
        return parent::getProfilePhotoAction($request);
    }
    
    public function searchUserAction(Request $request)
    {
        return parent::searchUserAction($request);
    } 
}


