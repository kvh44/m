<?php
namespace ApiBundle\Services;

use ApiBundle\Services\UtileService;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

class cacheService
{
    /**
     * @var Registry
     */
    protected $doctrine;

    /*
     * @var em
     */
    protected $em;
    
    /**
     * @var Translator
     */
    protected $translator;
    
    /**
     *
     * @var UtileService
     */
    protected $utileService;





    public function __construct(Registry $doctrine, Translator $translator, UtileService $utileService)
    {
        $this->doctrine = $doctrine;
        $this->em = $this->doctrine->getManager();
        $this->translator = $translator;
        $this->utileService = $utileService;
    }
    
    public function getSingleUserPageByIdentifier($identifier)
    {
        $user = $this->getSingleUserByIdentifierCache($identifier);
        $this->utileService->setResponseFrom(UtileService::FROM_CACHE);
        /*
        if(!$user){
            $user = $this->refreshSingleUserByIdentifierCache($identifier);
            $this->utileService->setResponseFrom(UtileService::FROM_SQL);
        }
        if(!$user){
            $this->utileService->setResponseMessage('user.identifier.wrong');
            $this->utileService->setResponseState(false);
            return $this->utileService->response;
        }
        
        $photos = $this->getSingleUserPhotosByUserIdCache($user->getId());
        if(!$photos){
            $photos = $this->refreshSingleUserPhotosByUserIdCache($user->getId());
            $this->utileService->setResponseFrom(UtileService::FROM_SQL);
        }
        */
        $this->utileService->setResponseData(array('user' => $user));
        $this->utileService->setResponseState(true);
        return $this->utileService->response;
    } 
    
    public function getSingleUserByIdentifierCache($identifier)
    {
        
    }  
    
    public function setSingleUserByIdentifierCache($identifier, $user)
    {
        
    }  
    
    public function removeSingleUserByIdentifierCache($identifier)
    {
        
    } 
    /*
    public function refreshSingleUserByIdentifierCache($identifier)
    {
        $user = $this->usersService->findUserByIdentifier($identifier);
        if(!$user){
            return null;
        }
        $this->setSingleUserByIdentifierCache($identifier, $user);
        return $user;
    }
    */
    public function getSingleUserPhotosByUserIdCache($user_id)
    {
        
    }
    
    public function setSingleUserPhotosByUserIdCache($user_id, $photos)
    {
        
    }
    
    public function removeSingleUserPhotosByUserIdCache($user_id)
    {
        
    }
    /*
    public function refreshSingleUserPhotosByUserIdCache($user_id)
    {
        $photos = $this->photoService->findPhotosByUserId($user_id);
        if(!$photos){
            $photos = array();
        }
        $this->setSingleUserPhotosByUserIdCache($user_id, $photos);
        return $photos;
    }
    */
}