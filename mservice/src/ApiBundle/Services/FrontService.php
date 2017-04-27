<?php
namespace ApiBundle\Services;

use ApiBundle\Services\UtileService;
use ApiBundle\Services\UsersService;
use ApiBundle\Services\PhotoService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

class FrontService
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
    
    
    
    protected $usersService;
    
    
    
    protected $photoService;




    public function __construct(Registry $doctrine, Translator $translator, UtileService $utileService, UsersService $usersService, PhotoService $photoService)
    {
        $this->doctrine = $doctrine;
        $this->em = $this->doctrine->getManager();
        $this->translator = $translator;
        $this->utileService = $utileService;
        $this->usersService = $usersService;
        $this->photoService = $photoService;
    }
    
    public function getSingleUserPageByIdentifier($identifier)
    {
        $user = $this->getSingleUserByIdentifierCache($identifier);
        $this->utileService->setResponseFrom(UtileService::FROM_CACHE);
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
        
        $this->utileService->setResponseData(array('user' => $user, 'photos' => $photos));
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
    
    public function refreshSingleUserByIdentifierCache($identifier)
    {
        $user = $this->usersService->findUserByIdentifier($identifier);
        if(!$user){
            return null;
        }
        $this->setSingleUserByIdentifierCache($identifier, $user);
        return $user;
    }

    public function getSingleUserPhotosByUserIdCache($user_id)
    {
        
    }
    
    public function setSingleUserPhotosByUserIdCache($user_id, $photos)
    {
        
    }
    
    public function removeSingleUserPhotosByUserIdCache($user_id)
    {
        
    }
    
    public function refreshSingleUserPhotosByUserIdCache($user_id)
    {
        $photos = $this->photoService->findPhotosByUserId($user_id);
        if(!$photos){
            $photos = array();
        }
        $this->setSingleUserPhotosByUserIdCache($user_id, $photos);
        return $photos;
    }
}