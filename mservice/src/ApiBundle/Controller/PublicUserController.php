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
    
    public function getSingleShopPageAction(Request $request)
    {
        $shop = $this->container->get('api_massage.UsersService')->getSingleShopPageByUsername($request->get('username'));
        return $shop;
    }

    public function removeSingleUserPageCacheAction(Request $request)
    {
       return $this->container->get('api_massage.CacheService')->removeSingleUserByUsernameCache($request->get('username'));
    }
    
    public function getSingleUserPhotosAction(Request $request)
    {
        return $this->container->get('api_massage.PhotoService')->findUserPhotosByUserIdCache($request->get('user_id'));
    }
    
    public function getProfilePhotoAction(Request $request)
    {
        return $this->container->get('api_massage.PhotoService')->findProfilePhotosByUserIdCache($request->get('user_id'));
    }
    
    public function searchUserAction(Request $request)
    {
        $offset = $request->get('offset')?$request->get('offset'):0;
        $limit = $request->get('limit')?$request->get('limit'):$this->getParameter('search.user.numberResults');
        $country_id = $request->get('country_id');
        $location_id = $request->get('location_id');
        $color = $request->get('color');
        $lang = $request->get('lang');
        $is_single = $request->get('is_single');
        $age_period = $request->get('age_period');
        $word = $request->get('word');
        $only_total = $request->get('only_total');
        return $this->container->get('api_massage.SearchService')->searchUserManager(
                $only_total, $offset, $limit, $country_id, $location_id, $color,
                $lang, $is_single, $age_period, $word);
    }           

    public function removeSearchUserCacheAction(Request $request)
    {
        $offset = $request->get('offset')?$request->get('offset'):0;
        $limit = $request->get('limit')?$request->get('limit'):$this->getParameter('search.user.numberResults');
        $country_id = $request->get('country_id');
        $location_id = $request->get('location_id');
        $color = $request->get('color');
        $lang = $request->get('lang');
        $is_single = $request->get('is_single');
        $age_period = $request->get('age_period');
        $word = $request->get('word');
        $only_total = $request->get('only_total');
        $key = $this->container->get('api_massage.SearchService')->getSearchUserKey(
                $only_total, $offset, $limit, $country_id, $location_id, $color,
                $lang, $is_single, $age_period, $word);
        return $this->container->get('api_massage.CacheService')->removeSearchUsersByKeyCache($key);
    }        
}
