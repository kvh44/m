<?php
namespace ApiBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use ApiBundle\Entity\Muser;
use ApiBundle\Entity\Mpassword;
use ApiBundle\Entity\Mphoto;
use ApiBundle\Entity\Mpost;
use ApiBundle\Entity\Mdraft;
use ApiBundle\Services\CacheService;
use ApiBundle\Services\PhotoService;

class DataPersist
{
    protected $cacheService;


    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if($entity instanceof Muser){
            $this->persistDate($entity);
            $this->updateUserCache($entity);
        }

        if($entity instanceof Mpassword){
            $this->persistDate($entity);
        }

        if($entity instanceof Mpost){
            $this->persistDate($entity);
        }

        if($entity instanceof Mphoto){
            $this->persistDate($entity);
        }

        if($entity instanceof Mdraft){
            $this->persistDate($entity);
        }

    }
    
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        
        if($entity instanceof Mphoto){
            $this->updateUserPhotosCache($entity);
        }
    }        

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if($entity instanceof Muser){
            $this->updateDate($entity);
            $this->updateUserCache($entity);
        }

        if($entity instanceof Mpassword){
            $this->updateDate($entity);
        }

        if($entity instanceof Mpost){
            $this->updateDate($entity);
        }

        if($entity instanceof Mphoto){
            $this->updateDate($entity);
        }

        if($entity instanceof Mdraft){
            $this->updateDate($entity);
        }

    }
    
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        
        if($entity instanceof Mphoto){
            $this->updateUserPhotosCache($entity);
        }
    } 
    
    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService; 
    }
    /*
    public function setCacheService(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }        
    */
    public function updateUserCache($entity)
    {
        $result = $this->cacheService->setSingleUserByUsernameCache($entity->getUsername(), serialize($entity));
        if($result){
            $entity->setIsSynchronizedByCache(1);
        } else {
            $entity->setIsSynchronizedByCache(0);
        }
    }
    
    public function updateUserPhotosCache($entity)
    {
        if($entity->getPhotoType() != PhotoService::PROFILE_PHOTO_TYPE && $entity->getPhotoType() != PhotoService::USER_PHOTO_TYPE){
            return;
        }
        $photos = $this->cacheService->container->get('doctrine')->getManager()->getRepository('ApiBundle:Mphoto')->loadPhotosByUserId($entity->getUser()->getId());
        $this->cacheService->setSingleUserPhotosByUserIdCache($entity->getUser()->getId(), serialize($photos));
    }        

    
    public function persistDate($entity)
    {
        $entity->setUpdated($this->getUpdated());
        $entity->setCreated($this->getCreated());
        return $entity;
    }

    public function updateDate($entity)
    {
        $entity->setUpdated($this->getUpdated());
        return $entity;
    }

    public function getCreated()
    {
        return new \DateTime('now');
    }

    public function getUpdated()
    {
        return new \DateTime('now');
    }
}