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
use ApiBundle\Services\UtileService;


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
        return ['prePersist', 'postPersist', 'preUpdate', 'postUpdate', 'preRemove', 'preFlush', 'postFlush'];
    }
    
    public function prePersist(LifecycleEventArgs $args)
    {
        try{
            $entity = $args->getObject();
            if ($entity instanceof Muser || $entity instanceof Mpost) {
                $entity = $this->testSearchEngineAliasesInfo($entity);
            }
        } catch (\Exception $e) {
            
        }
    }        

    public function postPersist(LifecycleEventArgs $args)
    {
		try{
			$entity = $args->getObject();

			if ($entity instanceof Muser || $entity instanceof Mpost) {
				
				if($entity->getIsdeleted()){
					$this->preRemove($args);
				}
				
				if ($this->objectPersister->handlesObject($entity)) {
					if ($this->isObjectIndexable($entity)) {
						$this->scheduledForInsertion[] = $entity;
					}
				}
			}
		} catch (\Exception $e) {
            
        }
    }
    
    public function preUpdate(LifecycleEventArgs $args)
    {
        try{
            $entity = $args->getObject();
            if ($entity instanceof Muser || $entity instanceof Mpost) {
                $entity = $this->testSearchEngineAliasesInfo($entity);
            }
        } catch (\Exception $e) {
 
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
		try{
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
		} catch (\Exception $e) {
            
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
    
    public function testSearchEngineAliasesInfo($entity)
    {
        $entity->setIsSynchronizedBySearch(UtileService::TINY_INT_FALSE);
        $aliasesInfo = $this->client->request('_aliases', 'GET')->getData();
        if(is_array($aliasesInfo)){
            $entity->setIsSynchronizedBySearch(UtileService::TINY_INT_TRUE);
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
