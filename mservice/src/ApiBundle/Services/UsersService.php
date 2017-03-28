<?php
namespace ApiBundle\Services;

use ApiBundle\Entity\ExtendedEntity\EMuser;
use ApiBundle\Entity\ExtendedEntity\EMpassword;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\DependencyInjection\Container;


class UsersService {
     /**
     * @var Registry
     */
     protected $doctrine;
     
     /*
      * @var em 
      */
     protected $em;
     /**
     * @var Session
     */
     protected $session;
     /**
     * @var Translator
     */
     protected $translator;
     
     /**
      *
      * @var UtileService 
      */
     protected $utileService;
     
     
     protected $mUser;
     
     
     protected $mPassword;

     public function __construct( Registry $doctrine, Session $session, Translator $translator, Container $container, UtileService $utileService)
     {
        $this->doctrine = $doctrine;
        $this->em = $this->doctrine->getManager();
        $this->session = $session;
        $this->translator = $translator;
        $this->container = $container;
        $this->utileService = $utileService;
     }  
     
     public function createUser($request) {
         $this->mUser = new EMuser();  
         $this->mPassword = new EMpassword();
        
         if(strlen($request->get('email')) > 0){
             if(!$this->validateEmailFormat($request->get('email'))){
                $this->utileService->response['error'] = 'email_invalid';
                $this->utileService->response['state'] = false;
             }
             if($this->findUserByEmail($request->get('email'))){
                $this->utileService->response['error'] = 'email_exist';
                $this->utileService->response['state'] = false;
             }
         }
         
         if($request->get('password1') !== $request->get('password2')) {
             $this->utileService->response['error'] = 'password_differ';
             $this->utileService->response['state'] = false;
         }
         
         if(strlen($request->get('password1')) < 6 || strlen($request->get('password2')) < 6) {
             $this->utileService->response['error'] = 'password_to_short';
             $this->utileService->response['state'] = false;
         }
         
         if($this->utileService->response['state'] === true) {
             $this->mUser->setUsername($request->get('username'));
             $this->mUser->setEmail($request->get('email'));
             $this->mUser->setTelephone($request->get('telephone'));
             $this->mUser->setSlug($request->get('username'));
             $this->mUser->setToken();
             $this->mUser->setInternalToken();
             $this->mUser->setExternalToken();
             $this->mUser->setInternalId();
             $this->mUser->setCreated(new \DateTime('now'));
             $this->mUser->setUpdated(new \DateTime('now'));
             $this->em->persist($this->mUser);
             
             $encodedPassword = $this->encryptPassword($request->get('password1'));
             $this->mPassword->setPassword($encodedPassword);
             $this->mPassword->setEncryptionMethod('bcrypt');
             $this->mPassword->setIndication($this->setPasswordIndication($request->get('password1')));
             $this->mPassword->setSalt('');
             $this->mPassword->setInternalId();
             $this->mPassword->setCreated(new \DateTime('now'));
             $this->mPassword->setUpdated(new \DateTime('now'));
             $this->mPassword->setUser($this->mUser);
             $this->em->persist($this->mPassword);
             $this->em->flush();
             $this->utileService->response['data'] = $this->mUser;
         }

         return $this->utileService->response;
     }
     
     public function encryptPassword($password, $algo = 'md5', $salt = null){
         $encoder = $this->container->get('security.password_encoder');
         $encodedPassword = $encoder->encodePassword($this->mUser, $password);
    
         return $encodedPassword;
     }
     
     
     public function setPasswordIndication($password){
         $length = strlen($password);
         $first = $password[0];
         $last = $password[$length - 1];
         
         $star = '';
         for($n = 0; $n < $length - 2; $n++){
             $star .= '*';
         }
         return $first .$star. $last;
     }
     
     public function validateEmailFormat($email) {
      return filter_var($email, FILTER_VALIDATE_EMAIL);
     }
     
     public function findUserByEmail($email){
         return $this->em->getRepository('ApiBundle:Muser')->loadUserByEmail($email);
     }
     
     public function login($username, $password){
         $user = $this->em->getRepository('ApiBundle:Muser')->loadUserByUsername($username);
         if($user) {
             if (password_verify($password, $user->getPassword())) { 
               $this->utileService->response['state'] = true;
               $this->utileService->response['data'] = $user;
               $this->utileService->response['error'] = null;
             } else {
               $this->utileService->response['state'] = false;
               $this->utileService->response['data'] = array();
               $this->utileService->response['error'] = 'password_incorrect';
             }
             return $this->utileService->response;
         }
     }
     
}
