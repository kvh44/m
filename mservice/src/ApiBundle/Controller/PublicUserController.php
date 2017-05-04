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
        
        $mngr = $this->get('fos_elastica.index_manager');

        $search = $mngr->getIndex('app')->createSearch();
        $search->addType('user');
        
        $boolQuery = new \Elastica\Query\BoolQuery();
        $categoryQuery = new \Elastica\Query\Terms();
        $categoryQuery->setTerms('isActive', array("1",true));
        $boolQuery->addMust($categoryQuery);
        
        $results = $search->search($boolQuery);

        return $results->getResults();
    }        
            
}
