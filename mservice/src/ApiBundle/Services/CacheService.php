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
    public $container;


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
        try{
            return $this->redisUser->hGet($this->userWithUsername,$username);
        } catch(\Exception $e) {
            return false;
        }
    }  
    
    public function setSingleUserByUsernameCache($username, $user)
    {
        try{
            return $this->redisUser->hSet($this->userWithUsername, $username, $user);
        } catch(\Exception $e) {
            return false;
        }
    }  
    
    public function removeSingleUserByUsernameCache($username)
    {
        try{
            return $this->redisUser->hDel($this->userWithUsername,$username);
        } catch(\Exception $e) {
            return false;
        }
    } 
    /*
    public function refreshSingleUserByIdentifierCache($identifier)
    {

    }
    */
    public function getSingleUserPhotosByUserIdCache($user_id)
    {
        try{
            return $this->redisUserPhotos->hGet($this->userPhotos,$user_id);
        } catch(\Exception $e) {
            return false;
        }
    }
    
    public function setSingleUserPhotosByUserIdCache($user_id, $photos)
    {
        try{
            return $this->redisUserPhotos->hSet($this->userPhotos, $user_id, $photos);
        } catch(\Exception $e) {
            return false;
        }
    }
    
    public function removeSingleUserPhotosByUserIdCache($user_id)
    {
        try{
            return $this->redisUserPhotos->hDel($this->userPhotos,$user_id);
        } catch(\Exception $e) {
            return false;
        }
    }
    /*
    public function refreshSingleUserPhotosByUserIdCache($user_id)
    {

    }
    */
}