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
    
    protected $photoService;


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
            $this->updateUserPhotosCache($entity);
        }

        if($entity instanceof Mdraft){
            $this->persistDate($entity);
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
            $this->updateUserPhotosCache($entity);
        }

        if($entity instanceof Mdraft){
            $this->updateDate($entity);
        }

    }
    
    public function __construct(CacheService $cacheService, PhotoService $photoService)
    {
        $this->cacheService = $cacheService;
        $this->photoService = $photoService;
    }
    /*
    public function setCacheService(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }        
    */
    public function updateUserCache($entity)
    {
        $this->cacheService->setSingleUserByUsernameCache($entity->getUsername(), serialize($entity));
        if($entity->getIsSynchronizedByCache() !== 1){
            $entity->setIsSynchronizedByCache(1);
        }
    }
    
    public function updateUserPhotosCache($entity)
    {
        if($entity->getPhotoType() !== PhotoService::PROFILE_PHOTO_TYPE && $entity->getPhotoType() !== PhotoService::USER_PHOTO_TYPE){
            return;
        }
        $photos = $this->photoService->findPhotosByUserId($entity->getUserId());
        $this->cacheService->setSingleUserPhotosByUserIdCache($entity->getUserId(), serialize($photos));
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