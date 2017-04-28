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
    
    
    protected $redisUserPhotos;




    public function __construct(Registry $doctrine, Translator $translator, Container $container, UtileService $utileService)
    {
        $this->doctrine = $doctrine;
        $this->em = $this->doctrine->getManager();
        $this->translator = $translator;
        $this->container = $container;
        $this->utileService = $utileService;
        $this->redisUser = $this->container->get('snc_redis.user');
        $this->redisUserPhotos = $this->container->get('snc_redis.userPhotos');
    }
    
    public function checkRedisRunning($redis)
    {
        try{
            $redis->ping();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }        


    public function getSingleUserByUsernameCache($username)
    {
        if(!$this->checkRedisRunning($this->redisUser)){
            return;
        }
        $user = $this->redisUser->hGet('userWithUsername',$username);
        return $user;
    }  
    
    public function setSingleUserByUsernameCache($username, $user)
    {
        if(!$this->checkRedisRunning($this->redisUser)){
            return;
        }
        $this->redisUser->hSet('userWithUsername', $username, $user);
        
    }  
    
    public function removeSingleUserByUsernameCache($username)
    {
        if(!$this->checkRedisRunning($this->redisUser)){
            return;
        }
        return $this->redisUser->hDel('userWithUsername',$username);
    } 
    /*
    public function refreshSingleUserByIdentifierCache($identifier)
    {

    }
    */
    public function getSingleUserPhotosByUserIdCache($user_id)
    {
        if(!$this->checkRedisRunning($this->redisUserPhotos)){
            return;
        }
        $photos = $this->redisUserPhotos->hGet('userPhotos',$user_id);
        return $photos;
    }
    
    public function setSingleUserPhotosByUserIdCache($user_id, $photos)
    {
        if(!$this->checkRedisRunning($this->redisUserPhotos)){
            return;
        }
        $this->redisUserPhotos->hSet('userPhotos', $user_id, $photos);
    }
    
    public function removeSingleUserPhotosByUserIdCache($user_id)
    {
        if(!$this->checkRedisRunning($this->redisUserPhotos)){
            return;
        }
        return $this->redisUserPhotos->hDel('userPhotos',$user_id);
    }
    /*
    public function refreshSingleUserPhotosByUserIdCache($user_id)
    {

    }
    */
}