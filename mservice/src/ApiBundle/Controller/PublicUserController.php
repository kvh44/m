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
        $user = $this->container->get('api_massage.CacheService')->getSingleUserByUsernameCache($request->get('username'));
        if($user){
            $this->container->get('api_massage.CacheService')->removeSingleUserByUsernameCache($request->get('username'));
            $user = unserialize($user);
            return $this->container->get('api_massage.CacheService')->removeSingleUserPhotosByUserIdCache($user->getId());
        } else {
            return 0;
        }
        
    }
    
    public function getSingleUserPhotosAction(Request $request)
    {
        return $this->container->get('api_massage.PhotoService')->findPhotosByUserIdCache($request->get('user_id'));
    }
    
    public function searchUserAction(Request $request)
    {
        /*
        $finder = $this->container->get('fos_elastica.finder.app.user');
        $results = $finder->find('anya17');
        */
        $offset = $request->get('offset');
        $length = $request->get('length');
        $country_id = $request->get('country_id');
        $location_id = $request->get('location_id');
        $color = $request->get('color');
        $lang = $request->get('lang');
        $is_single = $request->get('is_single');
        $age_period = $request->get('age_period');
        $word = $request->get('word');
        return $this->container->get('api_massage.SearchService')->searchManager(
                $offset, $length, $country_id, $location_id, $color,
                $lang, $is_single, $age_period, $word);
    }        
            
}
