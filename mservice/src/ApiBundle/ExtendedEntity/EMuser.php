<?php
namespace ApiBundle\ExtendedEntity;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use ApiBundle\Entity\Muser as Muser;
use ApiBundle\Services\UtileService;
/**
 * Description of EMuser
 *
 * @author renard
 */
class EMuser extends Muser{
    //put your code here
    
    public function setSlug($slug) {
        $slug = $slug . '-' . UtileService::getDateTimeMicroseconds();
        parent::setSlug($slug);
    }
    
    public function setToken($token = '') {
        $token = $token . UtileService::RandomString(32) . UtileService::getDateTimeMicroseconds();
        parent::setToken($token);
    }
    
    public function setExternalToken($token = '') {
        $token = $token . UtileService::RandomString(32) . UtileService::getDateTimeMicroseconds();
        parent::setExternalToken($token);
    }
    
    public function setInternalToken($token = '') {
        $token = $token . UtileService::RandomString(32) . UtileService::getDateTimeMicroseconds();
        parent::setInternalToken($token);
    }
    
    public function setInternalId($internalId = '') {
        $internalId = $internalId . UtileService::RandomString(32) . UtileService::getDateTimeMicroseconds();
        parent::setInternalId($internalId);
    }
}
