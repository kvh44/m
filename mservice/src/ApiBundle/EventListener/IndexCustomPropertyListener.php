<?php
namespace ApiBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FOS\ElasticaBundle\Event\TransformEvent;

use ApiBundle\Services\PhotoService;

class IndexCustomPropertyListener implements EventSubscriberInterface
{
    protected $photoService;
    
    public function __construct(PhotoService $photoService)
    {
        $this->photoService = $photoService;
    }
    
    public function addCustomProperty(TransformEvent $event)
    {
        $document = $event->getDocument();
        if(array_key_exists('username', $document->getData())){
            $data = $document->getData();
            $profile_photo = $this->photoService->findProfilePhotosByUserIdCache($data['id']);
            if(array_key_exists('data', $profile_photo)){
                if(array_key_exists('profile_photo', $profile_photo['data'])){
                    $document->set('profile_photo', $profile_photo['data']['profile_photo']);
                } else {
                    $document->set('profile_photo', $profile_photo['data']);
                }
            }
        }       
    }
    
    public static function getSubscribedEvents()
    {
        return array(
            TransformEvent::POST_TRANSFORM => 'addCustomProperty',
        );
    }
}