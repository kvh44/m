<?php
namespace ApiBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FOS\ElasticaBundle\Event\TransformEvent;

class IndexCustomPropertyListener implements EventSubscriberInterface
{
    public function addCustomProperty(TransformEvent $event)
    {
        $document = $event->getDocument();
        if(array_key_exists('username', $document->getData())){
            $document->set('custom', 'haha');
        }       
    }
    
    public static function getSubscribedEvents()
    {
        return array(
            TransformEvent::POST_TRANSFORM => 'addCustomProperty',
        );
    }
}