<?php
namespace ApiBundle\Services;

use ApiBundle\Services\UtileService;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\DependencyInjection\Container;


class CacheService
{
    /**
     * @var Registry
     */
    protected $doctrine;

    /*
     * @var em
     */
    protected $em;
    
    /**
     * @var Translator
     */
    protected $translator;
    
    /**
     *
     * @var UtileService
     */
    protected $utileService;

    /**
     * @var Container
     */
    protected $container;


    protected $redisUser;




    public function __construct(Registry $doctrine, Translator $translator, Container $container, UtileService $utileService)
    {
        $this->doctrine = $doctrine;
        $this->em = $this->doctrine->getManager();
        $this->translator = $translator;
        $this->container = $container;
        $this->utileService = $utileService;
        $this->redisUser = $this->container->get('snc_redis.user');
    }
    
    public function getSingleUserPageByIdentifier($identifier)
    {
        $user = $this->getSingleUserByIdentifierCache($identifier);
        $this->utileService->setResponseFrom(UtileService::FROM_CACHE);
        /*
        if(!$user){
            $user = $this->refreshSingleUserByIdentifierCache($identifier);
            $this->utileService->setResponseFrom(UtileService::FROM_SQL);
        }
        if(!$user){
            $this->utileService->setResponseMessage('user.identifier.wrong');
            $this->utileService->setResponseState(false);
            return $this->utileService->response;
        }
        
        $photos = $this->getSingleUserPhotosByUserIdCache($user->getId());
        if(!$photos){
            $photos = $this->refreshSingleUserPhotosByUserIdCache($user->getId());
            $this->utileService->setResponseFrom(UtileService::FROM_SQL);
        }
        */
        $this->utileService->setResponseData($user);
        $this->utileService->setResponseState(true);
        return $this->utileService->response;
    } 
    
    public function getSingleUserByUsernameCache($username)
    {
        $user = $this->redisUser->hGet('userWithUsername',$username);
        return $user;
    }  
    
    public function setSingleUserByUsernameCache($username, $user)
    {
        //foreach ($user as $key => $value){
            return $this->redisUser->hSet('userWithUsername', $username, $user);
        //}
    }  
    
    public function removeSingleUserByUsernameCache($username)
    {
        return $this->redisUser->hDel('userWithUsername',$username);
    } 
    /*
    public function refreshSingleUserByIdentifierCache($identifier)
    {
        $user = $this->usersService->findUserByIdentifier($identifier);
        if(!$user){
            return null;
        }
        $this->setSingleUserByIdentifierCache($identifier, $user);
        return $user;
    }
    */
    public function getSingleUserPhotosByUserIdCache($user_id)
    {
        
    }
    
    public function setSingleUserPhotosByUserIdCache($user_id, $photos)
    {
        
    }
    
    public function removeSingleUserPhotosByUserIdCache($user_id)
    {
        
    }
    /*
    public function refreshSingleUserPhotosByUserIdCache($user_id)
    {
        $photos = $this->photoService->findPhotosByUserId($user_id);
        if(!$photos){
            $photos = array();
        }
        $this->setSingleUserPhotosByUserIdCache($user_id, $photos);
        return $photos;
    }
    */
}