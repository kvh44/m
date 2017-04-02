<?php
namespace ApiBundle\Services;

use ApiBundle\Entity\Muser;
use ApiBundle\Entity\Mpassword;
use ApiBundle\Services\UtileService;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Validator\Validator\RecursiveValidator;
//use Symfony\Component\DependencyInjection\Container;


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
      * @var Validator 
      */
     protected $validator;


     /**
      *
      * @var UtileService 
      */
     protected $utileService;
     
     
     protected $mUser;
     
     
     protected $mPassword;
     
     const MIN_LENGTH_PASSWORD = 8;
     
     const MIN_LENGTH_TOKEN = 32;

     public function __construct( Registry $doctrine, Session $session, Translator $translator, RecursiveValidator $validator, UtileService $utileService)
     {
        $this->doctrine = $doctrine;
        $this->em = $this->doctrine->getManager();
        $this->session = $session;
        $this->translator = $translator;
        $this->validator = $validator;
        $this->utileService = $utileService;
     }  
     
     public function createUser($request) {
         try{
            $this->mUser = new Muser();  
            $this->mPassword = new Mpassword();

            if(strlen($request->get('email')) > 0){
                if(!$this->validateEmailFormat($request->get('email'))){
                   $this->utileService->setResponseErrorPath(array('field' => 'email')); 
                   $this->utileService->setResponseErrorMessage('user.email.format.invalid');
                   $this->utileService->setResponseState(false);
                }
                if($this->findUserByEmail($request->get('email'))){
                    $this->utileService->setResponseErrorPath(array('field' => 'email')); 
                   $this->utileService->setResponseErrorMessage('user.email.exist');
                   $this->utileService->setResponseState(false);
                }
            }
            /*
            if($this->findUserByTelephone($request->get('telephone'))){
                   $this->utileService->setResponseErrorMessage('telephone_exist');
                   $this->utileService->setResponseState(false);
            }
             */
            /*
            if($this->findUserByUsername($request->get('username'))){
                   $this->utileService->setResponseErrorMessage('username_exist');
                   $this->utileService->setResponseState(false);
            }
            */
            if($request->get('password1') !== $request->get('password2')) {
                $this->utileService->setResponseErrorPath(array('field' => array('password1', 'password2'))); 
                $this->utileService->setResponseErrorMessage('user.password.differ');
                $this->utileService->setResponseState(false);
            }
            /*
            if(strlen($request->get('password1')) < MIN_LENGTH_PASSWORD) {
                $this->utileService->setResponseErrorMessage('password_to_short');
                $this->utileService->setResponseState(false);
            }
            */
            if($this->utileService->getResponseState() === true) {
                $this->mUser->setUsername($request->get('username'));
                $this->mUser->setEmail($request->get('email'));
                $this->mUser->setTelephone($request->get('telephone'));
                $this->mUser->setSlug($this->prepareSlug($request->get('username')));
                $this->mUser->setToken($this->prepareToken());
                $this->mUser->setInternalToken($this->prepareInternalToken());
                $this->mUser->setExternalToken($this->prepareExternalToken());
                $this->mUser->setInternalId($this->prepareInternalId());
                if($request->get('is_shop') === 1) {
                    $this->mUser->setIsShop(1);
                    $this->mUser->setIsSingle(0);
                }
                if($request->get('is_zh')) {
                    $this->mUser->setIsZh(1);
                }
                if($request->get('is_fr')) {
                    $this->mUser->setIsFr(1);
                }
                if($request->get('is_en')) {
                    $this->mUser->setIsEn(1);
                }
                if($request->get('country_id')) {
                    $this->mUser->setCountryId($request->get('country_id'));
                }
                $this->mUser->setCreated(new \DateTime('now'));
                $this->mUser->setUpdated(new \DateTime('now'));


                $encodedPassword = $this->encryptPassword($request->get('password1'));
                $this->mPassword->setPassword($request->get('password1'));
                $this->mPassword->setEncryptionMethod('bcrypt');
                $this->mPassword->setIndication($this->preparePasswordIndication($request->get('password1')));
                $this->mPassword->setSalt(null);
                $this->mPassword->setInternalId($this->prepareInternalId());
                $this->mPassword->setCreated(new \DateTime('now'));
                $this->mPassword->setUpdated(new \DateTime('now'));
                $this->mPassword->setUser($this->mUser);


                $errorsPassword = $this->validator->validate($this->mPassword);
                if(count($errorsPassword)>0){
                    $message = $this->translator->trans(
                        $errorsPassword[0]->getMessage(), 
                        array(), 
                        'validators'
                    );
                    $this->utileService->setResponseErrorPath(array('field' => $errorsPassword[0]->getPropertyPath())); 
                    $this->utileService->setResponseErrorMessage($message);
                    $this->utileService->setResponseState(false);
                    return $this->utileService->response;
                }

                $errorsUser = $this->validator->validate($this->mUser);
                if(count($errorsUser)>0){
                    $message = $this->translator->trans(
                        $errorsUser[0]->getMessage(), 
                        array(), 
                        'validators'
                    );
                    $this->utileService->setResponseErrorPath(array('field' => $errorsUser[0]->getPropertyPath())); 
                    $this->utileService->setResponseErrorMessage($message);
                    $this->utileService->setResponseState(false);
                    return $this->utileService->response;
                }

                $this->em->persist($this->mUser);
                /*
                 * here we store the encoded password before persist!!!
                 */
                $this->mPassword->setPassword($encodedPassword);
                $this->em->persist($this->mPassword);

                $this->em->flush();
                $this->utileService->setResponseData($this->mUser);
                $this->utileService->setResponseFrom(UtileService::FROM_SQL);
            }

            return $this->utileService->response;
         } catch (\Exception $e){
             $this->utileService->setResponseErrorMessage($e->getMessage());
             $this->utileService->setResponseState(false);
             return $this->utileService->response;
         }
     }
     
     public function prepareSlug($slug) {
        return $slug . '-' . UtileService::getDateTimeMicroseconds();
     }
    
     public function prepareToken($token = '') {
        return $token . UtileService::RandomString(self::MIN_LENGTH_TOKEN) . UtileService::getDateTimeMicroseconds();
    }
    
    public function prepareExternalToken($token = '') {
        return $token . UtileService::RandomString(self::MIN_LENGTH_TOKEN) . UtileService::getDateTimeMicroseconds();
    }
    
    public function prepareInternalToken($token = '') {
        return $token . UtileService::RandomString(self::MIN_LENGTH_TOKEN) . UtileService::getDateTimeMicroseconds();
    }
    
    public function prepareInternalId($internalId = '') {
        return $internalId . UtileService::RandomString(self::MIN_LENGTH_TOKEN) . UtileService::getDateTimeMicroseconds();
    }
     
     public function encryptPassword($password, $algo = 'bcrypt', $salt = null){
         return password_hash($password, PASSWORD_BCRYPT);
     }
     
     /**
      * 
      * @param type $password
      * @return string
      */
     public function preparePasswordIndication($password){
         return UtileService::prepareIndication($password);
     }
     
     /**
      * 
      * @param type $email
      * @return Email
      */
     public function validateEmailFormat($email) {
      return UtileService::validateEmailFormat($email);
     }
     
     /**
      * 
      * @param type $email
      * @return Muser or NULL
      */
     public function findUserByEmail($email){
         return $this->em->getRepository('ApiBundle:Muser')->loadUserByEmail($email);
     }
     
     /**
      * 
      * @param type $telephone
      * @return Muser or NULL
      */
     public function findUserByTelephone($telephone) {
         return $this->em->getRepository('ApiBundle:Muser')->loadUserByTelephone($telephone);
     }
     
     /**
      * 
      * @param type $username
      * @return Muser or NULL
      */
     public function findUserByUsername($username) {
         return $this->em->getRepository('ApiBundle:Muser')->loadUserByUsername($username);
     }
     
     /**
      * 
      * @param type $identifier
      * @return Muser or NULL
      */
     public function findUserByIdentifier($identifier) {
         return $this->em->getRepository('ApiBundle:Muser')->loadUserByIdentifier($identifier);
     }
     
     
     public function findPasswordByUserInternalId($internal_id){
         return $this->em->getRepository('ApiBundle:Muser')->loadPasswordByUserInternalId($internal_id);
     }
             

     /**
      * 
      * @param $request
      * @return response
      */
     public function login($request){
         $user = $this->findUserByIdentifier($request->get('identifier'));
         if($user) {
             $mPassword = $this->findPasswordByUserInternalId($user->getInternalId());
             if (password_verify($request->get('password'), $mPassword['password'])) { 
               $this->utileService->setResponseState(true);
               $this->utileService->setResponseData($user);
               $this->utileService->setResponseErrorMessage(null);
             } else {               
                 $this->utileService->setResponseState(false);
               $this->utileService->setResponseData(array());
               $this->utileService->setResponseErrorMessage('user.password.incorrect');
             }             
         } else {
             $this->utileService->setResponseState(false);
             $this->utileService->setResponseData(array());
             $this->utileService->setResponseErrorMessage('user.username.incorrect');
         }
         return $this->utileService->response;
     }
     
}
