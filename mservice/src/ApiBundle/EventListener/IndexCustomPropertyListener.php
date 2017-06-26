<?php
namespace ApiBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FOS\ElasticaBundle\Event\TransformEvent;

use ApiBundle\Services\PhotoService;
use ApiBundle\Services\UtileService;

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
        // user or shop
        if(array_key_exists('username', $document->getData())){
            $data = $document->getData();
            
            $profile_photo = $this->photoService->findProfilePhotosByUserIdCache($data['id']);
            if(array_key_exists('data', $profile_photo)){
                if(array_key_exists(UtileService::DATA_STRUCTURE_PROFILE_PHOTO, $profile_photo['data'])){
                    $document->set(UtileService::DATA_STRUCTURE_PROFILE_PHOTO, $profile_photo['data'][UtileService::DATA_STRUCTURE_PROFILE_PHOTO]);
                } else {
                    $document->set(UtileService::DATA_STRUCTURE_PROFILE_PHOTO, $profile_photo[UtileService::DATA_STRUCTURE_PROFILE_PHOTO]);
                }
            }
            
        }
        
        //post
    }
    
    public static function getSubscribedEvents()
    {
        return array(
            TransformEvent::POST_TRANSFORM => 'addCustomProperty',
        );
    }
}