<?php
namespace ApiBundle\Services;

use ApiBundle\Services\UtileService;
use Symfony\Component\DependencyInjection\Container;


class CacheService
{
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
    
    
    protected $userWithUsername;


    protected $userPhotos;

    public function __construct(Container $container, UtileService $utileService, $userWithUsername, $userPhotos)
    {
        $this->container = $container;
        $this->utileService = $utileService;
        $this->redisUser = $this->container->get('snc_redis.user');
        $this->redisUserPhotos = $this->container->get('snc_redis.userPhotos');
        $this->userWithUsername = $userWithUsername;
        $this->userPhotos = $userPhotos;
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
        return $this->redisUser->hGet($this->userWithUsername,$username);
    }  
    
    public function setSingleUserByUsernameCache($username, $user)
    {
        if(!$this->checkRedisRunning($this->redisUser)){
            return;
        }
        return $this->redisUser->hSet($this->userWithUsername, $username, $user);
    }  
    
    public function removeSingleUserByUsernameCache($username)
    {
        if(!$this->checkRedisRunning($this->redisUser)){
            return;
        }
        return $this->redisUser->hDel($this->userWithUsername,$username);
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
        return $this->redisUserPhotos->hGet($this->userPhotos,$user_id);
    }
    
    public function setSingleUserPhotosByUserIdCache($user_id, $photos)
    {
        if(!$this->checkRedisRunning($this->redisUserPhotos)){
            return;
        }
        return $this->redisUserPhotos->hSet($this->userPhotos, $user_id, $photos);
    }
    
    public function removeSingleUserPhotosByUserIdCache($user_id)
    {
        if(!$this->checkRedisRunning($this->redisUserPhotos)){
            return;
        }
        return $this->redisUserPhotos->hDel($this->userPhotos,$user_id);
    }
    /*
    public function refreshSingleUserPhotosByUserIdCache($user_id)
    {

    }
    */
}