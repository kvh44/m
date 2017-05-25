<?php
namespace ApiBundle\EventListener;

use ApiBundle\Entity\Muser;
use ApiBundle\Entity\Mpost;

use Doctrine\Common\EventSubscriber;
use FOS\ElasticaBundle\Doctrine\Listener;
use FOS\ElasticaBundle\Persister\ObjectPersisterInterface;
use FOS\ElasticaBundle\Provider\IndexableInterface;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\PropertyAccess\PropertyAccess;
use FOS\ElasticaBundle\Elastica\Client;


class IndexListener extends Listener implements EventSubscriber
{
    protected $propertyAccessor;

    private $indexable;
    private $config;

    public function __construct(
        ObjectPersisterInterface $userPersister,
        IndexableInterface $indexable,
        array $config
    ) {
        $this->objectPersister = $userPersister;
        $this->indexable = $indexable;
        $this->config = $config;
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        $this->client = new Client();
    }

    public function getSubscribedEvents()
    {
        return ['postPersist', 'postUpdate', 'preRemove', 'preFlush', 'postFlush'];
    }
    
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();
        if ($entity instanceof Muser || $entity instanceof Mpost) {
            $entity = $this->testAliasesInfo($entity);
        }
    }        

    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        if ($entity instanceof Muser || $entity instanceof Mpost) {
            
            if($entity->getIsdeleted()){
                $this->preRemove($eventArgs);
            }
            
            if ($this->objectPersister->handlesObject($entity)) {
                if ($this->isObjectIndexable($entity)) {
                    $this->scheduledForInsertion[] = $entity;
                }
            }
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Muser || $entity instanceof Mpost) {

            if($entity->getIsdeleted()){
                $this->preRemove($args);
            }
        
            if ($this->objectPersister->handlesObject($entity)) {
                if ($this->isObjectIndexable($entity)) {
                    $this->scheduledForUpdate[] = $entity;
                }
            }
        }
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Muser || $entity instanceof Mpost) {
            if ($this->objectPersister->handlesObject($entity)) {
                if ($identifierValue = $this->propertyAccessor->getValue($entity, $this->config['identifier'])) {
                    $this->scheduledForDeletion[] = $identifierValue;
                }
            }
        }
    }
    
    public function testAliasesInfo($entity)
    {
        $aliasesInfo = $this->client->request('_aliases', 'GET')->getData();
        if(is_array($aliasesInfo)){
            $entity->setIsSynchronizedBySearch(1);
        }
        return $entity;
    }

    private function isObjectIndexable($object)
    {
        return $this->indexable->isObjectIndexable(
            $this->config['index'],
            $this->config['type'],
            $object
        );
    }
}
