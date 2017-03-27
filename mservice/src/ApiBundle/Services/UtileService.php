<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApiBundle\Services;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

class UtileService {
    
    /**
     * @var Translator
     */
    protected $translator;
    
    public function __construct(Translator $translator)
     {
        $this->translator = $translator;
     }  
    
    public $response= array('data' => array(), 'state' => true, 'error' => '');
    
    public $errors = array(
    );
    public function getResponse() {
        return $this->response;
    }
    
    public function getError(){
        return $this->errors;
    }
}
