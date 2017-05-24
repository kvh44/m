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
    
    
    protected $redisProfilePhoto;
	
	
    protected $redisPost;


    protected $redisPostPhotos;


    protected $userWithUsername;


    protected $userPhotos;
    
    
    protected $profilePhotos;
	
	
	protected $post;
	
	
	protected $postPhotos;

    
    public function __construct(Container $container, UtileService $utileService, $userWithUsername, $userPhotos, $profilePhotos, $post, $postPhotos)
    {
        $this->container = $container;
        $this->utileService = $utileService;
        $this->redisUser = $this->container->get('snc_redis.user');
        $this->redisUserPhotos = $this->container->get('snc_redis.userPhotos');
        $this->redisProfilePhoto = $this->container->get('snc_redis.profilePhotos');
		$this->redisPost = $this->container->get('snc_redis.post');
		$this->redisPostPhotos = $this->container->get('snc_redis.postPhotos');
        $this->userWithUsername = $userWithUsername;
        $this->userPhotos = $userPhotos;
        $this->profilePhotos = $profilePhotos;
		$this->post = $post;
		$this->postPhotos = $postPhotos;
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
            $user = $this->getSingleUserByUsernameCache($username);
            if($user){
                $user = unserialize($user);
                $this->removeSingleUserPhotosByUserIdCache($user->getId());
                $this->removeProfilePhotoByUserIdCache($user->getId());
            }
            return $this->redisUser->hDel($this->userWithUsername,$username);
        } catch(\Exception $e) {
            return false;
        }
    } 

    public function getProfilePhotoByUserIdCache($user_id)
    {
        try{
            return $this->redisProfilePhoto->hGet($this->profilePhotos,$user_id);
        } catch(\Exception $e) {
            return false;
        }
    }
    
    public function setProfilePhotoByUserIdCache($user_id, $photo)
    {
        try{
            return $this->redisProfilePhoto->hSet($this->profilePhotos, $user_id, $photo);
        } catch(\Exception $e) {
            return false;
        }
    }
    
    public function removeProfilePhotoByUserIdCache($user_id)
    {
        try{
            return $this->redisProfilePhoto->hDel($this->profilePhotos,$user_id);
        } catch(\Exception $e) {
            return false;
        }
    }

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
	
	public function getSinglePostCache($id)
    {
        try{
            return $this->redisPost->hGet($this->post,$id);
        } catch(\Exception $e) {
            return false;
        }
    }  
    
    public function setSinglePostCache($id, $post)
    {
        try{
            return $this->redisPost->hSet($this->post, $id, $post);
        } catch(\Exception $e) {
            return false;
        }
    }  
    
    public function removeSinglePostCache($id)
    {
        try{
            $post = $this->getSinglePostCache($id);
            if($post){
                $post = unserialize($post);
                $this->removeSinglePostPhotosByPostIdCache($id);
            }
            return $this->redisPost->hDel($this->post,$id);
        } catch(\Exception $e) {
            return false;
        }
    } 
	
	public function getSinglePostPhotosByPostIdCache($post_id)
    {
        try{
            return $this->redisPostPhotos->hGet($this->postPhotos,$post_id);
        } catch(\Exception $e) {
            return false;
        }
    }
    
    public function setSinglePostPhotosByPostIdCache($post_id, $photos)
    {
        try{
            return $this->redisPostPhotos->hSet($this->postPhotos, $post_id, $photos);
        } catch(\Exception $e) {
            return false;
        }
    }
    
    public function removeSinglePostPhotosByPostIdCache($post_id)
    {
        try{
            return $this->redisPostPhotos->hDel($this->postPhotos,$post_id);
        } catch(\Exception $e) {
            return false;
        }
    }
	
}