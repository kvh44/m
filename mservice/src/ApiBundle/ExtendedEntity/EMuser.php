<?php
namespace ApiBundle\ExtendedEntity;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use ApiBundle\Entity\Muser as Muser;
/**
 * Description of EMuser
 *
 * @author renard
 */
class EMuser extends Muser{
    //put your code here
    
    public function setSlug($slug) {
        $slug = $slug . '-' . date("YmdHis");
        parent::setSlug($slug);
    }
    
    public function setToken($token) {
        $token = $token . mt_rand() . date("YmdHis");
        parent::setToken($token);
    }
    
    public function setExternalToken($token) {
        $token = $token . mt_rand() . date("YmdHis");
        parent::setExternalToken($token);
    }
    
    public function setInternalToken($token) {
        $token = $token . mt_rand() . date("YmdHis");
        parent::setInternalToken($token);
    }
}
