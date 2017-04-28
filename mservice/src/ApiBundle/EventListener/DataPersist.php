<?php
namespace ApiBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use ApiBundle\Entity\Muser;
use ApiBundle\Entity\Mpassword;
use ApiBundle\Entity\Mphoto;
use ApiBundle\Entity\Mpost;
use ApiBundle\Entity\Mdraft;

class DataPersist
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if($entity instanceof Muser){
            $this->persistData($entity);
        }

        if($entity instanceof Mpassword){
            $this->persistData($entity);
        }

        if($entity instanceof Mpost){
            $this->persistData($entity);
        }

        if($entity instanceof Mphoto){
            $this->persistData($entity);
        }

        if($entity instanceof Mdraft){
            $this->persistData($entity);
        }

    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if($entity instanceof Muser){
            $this->updateData($entity);
        }

        if($entity instanceof Mpassword){
            $this->updateData($entity);
        }

        if($entity instanceof Mpost){
            $this->updateData($entity);
        }

        if($entity instanceof Mphoto){
            $this->updateData($entity);
        }

        if($entity instanceof Mdraft){
            $this->updateData($entity);
        }

    }

    public function persistData($entity)
    {
        $entity->setUpdated($this->getUpdated());
        if(!$entity->getCreated()){
            $entity->setCreated($this->getCreated());
        }
        return $entity;
    }

    public function updateData($entity)
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