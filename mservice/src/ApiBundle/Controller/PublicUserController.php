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
        return $this->container->get('api_massage.UsersService')->login($request);
    }        
    
    public function createUserAction(Request $request)
    {
        return $this->container->get('api_massage.UsersService')->createUser($request);
    }
    
    public function forgetPasswordAction(Request $request)
    {
        return $this->container->get('api_massage.UsersService')->forgetPassword($request);
    }    

    public function resetPasswordAction(Request $request)
    {
        return $this->container->get('api_massage.UsersService')->resetPassword($request);
    }

    public function getSingleUserPageAction(Request $request)
    {
        return $this->container->get('api_massage.UsersService')->getSingleUserPageByUsername($request->get('username'));
    }

    public function removeSingleUserPageCacheAction(Request $request)
    {
        return $this->container->get('api_massage.CacheService')->removeSingleUserByUsernameCache($request->get('username'));
    }
    
    public function getSingleUserPhotosAction(Request $request)
    {
        return $this->container->get('api_massage.PhotoService')->findPhotosByUserIdCache($request->get('user_id'));
    }
            
}
