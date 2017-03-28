<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApiBundle\Entity\ExtendedEntity;

use ApiBundle\Entity\Mpassword;
use ApiBundle\Services\UtileService;
/**
 * Description of EMpassword
 *
 * @author renard
 */
class EMpassword extends Mpassword{
    //put your code here
    
    public function setIndication($indication = null) {
        if(!$indication){
            $length = strlen($this->password);
            $first = $this->password[0];
            $last = $this->password[$length - 1];

            $star = '';
            for($n = 0; $n < $length - 2; $n++){
                $star .= '*';
            }
            $indication = $first .$star. $last;
        }
        parent::setIndication($indication);
    }
    
    public function setSlug($slug) {
        $slug = $slug . '-' . UtileService::getDateTimeMicroseconds();
        parent::setSlug($slug);
    }
    
    public function setInternalId($internalId = '') {
        $internalId = $internalId . UtileService::RandomString(32) . UtileService::getDateTimeMicroseconds();
        parent::setInternalId($internalId);
    }
}
