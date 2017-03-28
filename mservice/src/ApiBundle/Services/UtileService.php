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
    
    public function setResponse($response) {
        $this->response = $response;
    }

    public function getResponse() {
        return $this->response;
    }
    
    public function setErrors($errors){
        $this->errors = $errors;
    }

    public function getErrors(){
        return $this->errors;
    }
    
    public static function RandomString($length = 32) {
        $keys = array_merge(range(0,9), range('a', 'z'), range('A', 'Z'));

        $key = "";
        for($i=0; $i < $length; $i++) {
            $key .= $keys[mt_rand(0, count($keys) - 1)];
        }
        return $key;
    }
    
    public static function getDateTimeMicroseconds(){
        return date("YmdHis").substr((string)microtime(), 1, 8);
    }
}
