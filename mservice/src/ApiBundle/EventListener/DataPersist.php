<?php
namespace ApiBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use ApiBundle\Entity\Muser;
use ApiBundle\Entity\Mpassword;
use ApiBundle\Entity\Mphoto;
use ApiBundle\Entity\Mpost;
use ApiBundle\Entity\Mdraft;
use ApiBundle\Services\CacheService;

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
        $this->cacheService->setSingleUserByUsernameCache($entity->getUsername(), serialize($entity));
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