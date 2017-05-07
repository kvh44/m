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
     
    const FROM_SQL = 'sql';
    const FROM_CACHE = 'cache';
    const FROM_SEARCH = 'search';
    
    const CONST_NULL = NULL;
    const BOOL_TRUE = true;
    const TINY_INT_TRUE = 1;
    const TINY_INT_TRUE_STRING = '1';
    const BOOL_FALSE = false;
    const TINY_INT_FALSE = 0;
    const TINY_INT_FALSE_STRING = '0';
    
    const LANG_ZH = 'zh';
    const LANG_FR = 'fr';
    const LANG_EN = 'en';
    
    public $response= array('data' => array(), 'state' => true, 'message' => null, 'path' => null, 'from' => null,'code' => 0);
    
    public $errors = array();
    
    public function setResponseData($data = array()) {
        $this->response['data'] = $data;
    }
    
    public function setResponseState($state = true) {
        $this->response['state'] = $state;
    }
    
    public function setResponseMessage($message = null) {
        $this->response['message'] = $message;
    }
    
    public function setResponseCode($code = 0) {
        $this->response['code'] = $code;
    }
    
    public function setResponsePath($path = null){
        $this->response['path'] = $path;
    }
    
    public function setResponseFrom($from = null){
        $this->response['from'] = $from;
    }

    public function setResponse($response) {
        $this->response = $response;
    }
    
    public function getResponseState() {
        return $this->response['state'];
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
    
    public static function validateEmailFormat($email) {
      return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    public static function prepareIndication($string, $mask = '*'){
         $length = strlen($string);
         $first = $string[0];
         $last = $string[$length - 1];
         
         $star = '';
         for($n = 0; $n < $length - 2; $n++){
             $star .= $mask;
         }
         return $first .$star. $last;
     }
}
