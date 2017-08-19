<?php
namespace ApiBundle\Services;

use ApiBundle\Entity\Muser;
use ApiBundle\Entity\Mpassword;
use ApiBundle\Services\UtileService;
use ApiBundle\Services\CacheService;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ApiBundle\Services\MailerService;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\DependencyInjection\Container;


class UsersService
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
     * @var Container
     */
    protected $container;

    /**
     *
     * @var UtileService
     */
    protected $utileService;




    protected $cacheService;
    
    
    /**
     *
     * @var Mailer 
     */
    protected $mailer;


    /**
     * @var Muser
     */
    protected $mUser;

    /**
     * @var Mpassword
     */
    protected $mPassword;
    
    protected $min_top_time;


    
    const MIN_LENGTH_PASSWORD = 8;
    const MIN_LENGTH_TOKEN = 32;
    const ENCRYPTION_METHOD = 'bcrypt';

    public function __construct(Registry $doctrine, Session $session, Translator $translator, RecursiveValidator $validator, Container $container,UtileService $utileService, MailerService $mailer, CacheService $cacheService, $min_top_time)
    {
        $this->doctrine = $doctrine;
        $this->em = $this->doctrine->getManager();
        $this->session = $session;
        $this->translator = $translator;
        $this->validator = $validator;
        $this->container = $container;
        $this->utileService = $utileService;
        $this->mailer = $mailer;
        $this->cacheService = $cacheService;
        $this->minTopTime = $min_top_time;
    }

    public function createUser($request)
    {
        try {
            $this->mUser = new Muser();
            $this->mPassword = new Mpassword();

            if (strlen($request->get('email')) > 0) {
                if (!$this->validateEmailFormat($request->get('email'))) {
                    $this->utileService->setResponsePath(array('field' => 'email'));
                    $this->utileService->setResponseMessage($this->translator->trans('user.email.format.invalid'));
                    $this->utileService->setResponseState(false);
                    return $this->utileService->getResponse();
                }
                if ($this->findUserByEmail($request->get('email'))) {
                    $this->utileService->setResponsePath(array('field' => 'email'));
                    $this->utileService->setResponseMessage($this->translator->trans('user.email.exist'));
                    $this->utileService->setResponseState(false);
                    return $this->utileService->getResponse();
                }
            }

            if ($user = $this->findUserByTelephone($request->get('telephone'))) {
                $this->utileService->setResponseData($user);
                $this->utileService->setResponseMessage($this->translator->trans('user.telephone.exist'));
                $this->utileService->setResponseState(false);
                return $this->utileService->getResponse();
            }


            if ($user = $this->findUserByUsername($request->get('username'))) {
                $this->utileService->setResponseData($user);
                $this->utileService->setResponseMessage($this->translator->trans('user.username.exist'));
                $this->utileService->setResponseState(false);
                return $this->utileService->getResponse();
            }

            if ($request->get('password1') !== $request->get('password2')) {
                $this->utileService->setResponsePath(array('field' => array('password1', 'password2')));
                $this->utileService->setResponseMessage($this->translator->trans('user.password.differ'));
                $this->utileService->setResponseState(false);
                return $this->utileService->getResponse();
            }
            /*
            if(strlen($request->get('password1')) < MIN_LENGTH_PASSWORD) {
                $this->utileService->setResponseMessage('password_to_short');
                $this->utileService->setResponseState(false);
            }
            */
            if ($this->utileService->getResponseState() === true) {
                $this->mUser->setUsername($request->get('username'));
                $this->mUser->setEmail($request->get('email'));
                $this->mUser->setTelephone($request->get('telephone'));
                $this->mUser->setSlug($this->prepareSlug($request->get('username')));
                $this->mUser->setToken($this->prepareToken());
                $this->mUser->setInternalToken($this->prepareInternalToken());
                $this->mUser->setExternalToken($this->prepareExternalToken());
                $this->mUser->setInternalId($this->prepareInternalId());
                if ($request->get('is_shop') == 1) {
                    $this->mUser->setIsShop(1);
                    $this->mUser->setIsSingle(0);
                }
                if ($request->get('is_zh') == 1) {
                    $this->mUser->setIsZh(1);
                }
                if ($request->get('is_fr') == 1) {
                    $this->mUser->setIsFr(1);
                }
                if ($request->get('is_en') == 1) {
                    $this->mUser->setIsEn(1);
                }
                if ($request->get('country_id')) {
                    $this->mUser->setCountryId($request->get('country_id'));
                }
                //$this->mUser->setCreated(new \DateTime('now'));
                //$this->mUser->setUpdated(new \DateTime('now'));


                $encodedPassword = $this->encryptPassword($request->get('password1'));
                $this->mPassword->setPassword($request->get('password1'));
                $this->mPassword->setEncryptionMethod(self::ENCRYPTION_METHOD);
                $this->mPassword->setIndication($this->preparePasswordIndication($request->get('password1')));
                $this->mPassword->setSalt(null);
                $this->mPassword->setInternalId($this->prepareInternalId());
                //$this->mPassword->setCreated(new \DateTime('now'));
                //$this->mPassword->setUpdated(new \DateTime('now'));
                $this->mPassword->setUser($this->mUser);


                $errorsPassword = $this->validator->validate($this->mPassword);
                if (count($errorsPassword) > 0) {
                    $message = $this->translator->trans(
                        $errorsPassword[0]->getMessage()
                    );
                    $this->utileService->setResponsePath(array('field' => $errorsPassword[0]->getPropertyPath()));
                    $this->utileService->setResponseMessage($message);
                    $this->utileService->setResponseState(false);
                    return $this->utileService->getResponse();
                }

                $errorsUser = $this->validator->validate($this->mUser);
                if (count($errorsUser) > 0) {
                    $message = $this->translator->trans(
                        $errorsUser[0]->getMessage()
                    );
                    $this->utileService->setResponsePath(array('field' => $errorsUser[0]->getPropertyPath()));
                    $this->utileService->setResponseMessage($message);
                    $this->utileService->setResponseState(false);
                    return $this->utileService->getResponse();
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
                //$this->mailer->sendNewUserMail();
            }

            return $this->utileService->getResponse();
        } catch (\Exception $e) {
            $this->utileService->setResponseMessage($e->getMessage());
            $this->utileService->setResponseState(false);
            return $this->utileService->getResponse();
        }
    }

    public function prepareSlug($slug)
    {
        return $slug . '-' . UtileService::getDateTimeMicroseconds();
    }

    public function prepareToken($token = '')
    {
        return $token . UtileService::RandomString(self::MIN_LENGTH_TOKEN) . UtileService::getDateTimeMicroseconds();
    }

    public function prepareExternalToken($token = '')
    {
        return $token . UtileService::RandomString(self::MIN_LENGTH_TOKEN) . UtileService::getDateTimeMicroseconds();
    }

    public function prepareInternalToken($token = '')
    {
        return $token . UtileService::RandomString(self::MIN_LENGTH_TOKEN) . UtileService::getDateTimeMicroseconds();
    }

    public function prepareInternalId($internalId = '')
    {
        return $internalId . UtileService::RandomString(self::MIN_LENGTH_TOKEN) . UtileService::getDateTimeMicroseconds();
    }

    public function encryptPassword($password, $algo = self::ENCRYPTION_METHOD, $salt = null)
    {
        if($algo === self::ENCRYPTION_METHOD){
            return password_hash($password, PASSWORD_BCRYPT);
        }
        return $password;
    }

    /**
     *
     * @param type $password
     * @return string
     */
    public function preparePasswordIndication($password)
    {
        return UtileService::prepareIndication($password);
    }

    /**
     *
     * @param type $email
     * @return Email
     */
    public function validateEmailFormat($email)
    {
        return UtileService::validateEmailFormat($email);
    }

    /**
     *
     * @param type $email
     * @return Muser or NULL
     */
    public function findUserByEmail($email)
    {
        return $this->em->getRepository('ApiBundle:Muser')->loadUserByEmail($email);
    }

    /**
     *
     * @param type $telephone
     * @return Muser or NULL
     */
    public function findUserByTelephone($telephone)
    {
        return $this->em->getRepository('ApiBundle:Muser')->loadUserByTelephone($telephone);
    }

    /**
     *
     * @param type $username
     * @return Muser or NULL
     */
    public function findUserByUsername($username)
    {
        return $this->em->getRepository('ApiBundle:Muser')->loadUserByUsername($username);
    }
    
    /**
     *
     * @param type $internal_id
     * @return Muser or NULL
     */
    public function findUserByInternalId($internal_id)
    {
        return $this->em->getRepository('ApiBundle:Muser')->loadUserByInternalId($internal_id);
    }

    /**
     *
     * @param type $identifier
     * @return Muser or NULL
     */
    public function findUserByIdentifier($identifier)
    {
        return $this->em->getRepository('ApiBundle:Muser')->loadUserByIdentifier($identifier);
    }

    public function findPasswordIndicationByIdentifier($identifier)
    {
        return $this->em->getRepository('ApiBundle:Muser')->loadPasswordIndicationByIdentifier($identifier);
    }

    public function findPasswordByUserInternalId($internal_id)
    {
        return $this->em->getRepository('ApiBundle:Muser')->loadPasswordByUserInternalId($internal_id);
    }

    public function findInternalTokenByExternalToken($internal_id, $external_token)
    {
        return $this->em->getRepository('ApiBundle:Muser')->loadInternalTokenByExternalToken($internal_id, $external_token);
    }
    
    public function findUserByInternalToken($internal_token)
    {
        return $this->em->getRepository('ApiBundle:Muser')->loadUserByInternalToken($internal_token);
    }
    
    public function findUserByToken($token)
    {
        return $this->em->getRepository('ApiBundle:Muser')->loadUserByToken($token);
    }
    
    public function findUserPhotosByUserId($user_id)
    {
        return $this->em->getRepository('ApiBundle:Mphoto')->loadUserPhotosByUserId($user_id);
    }
    
    public function findProfilePhotoByUserId($user_id)
    {
        return $this->em->getRepository('ApiBundle:Mphoto')->loadProfilePhotosByUserId($user_id);
    }

    private function refreshAlltokensForUser($internalId, $internalToken)
    {
        try {
            $user = $this->findUserByInternalToken($internalToken);
            if (!$user) {
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseData(array());
                $this->utileService->setResponseMessage($this->translator->trans('user.internal_token.wrong'));
            }

            if ($user->getInternalId() === $internalId) {
                $user->setToken($this->prepareToken());
                $user->setInternalToken($this->prepareInternalToken());
                $user->setExternalToken($this->prepareExternalToken());
                $this->em->persist($user);
                $this->em->flush();

                $this->utileService->setResponseState(true);
                $this->utileService->setResponseData(array());
                $this->utileService->setResponseMessage($this->translator->trans('user.token.changed'));
            } else {
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseData(array());
                $this->utileService->setResponseMessage($this->translator->trans('user.internal_id.wrong'));
            }
        } catch (\Exception $e) {
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseData(array());
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->getResponse();
        }
        return $this->utileService->getResponse();

    }

    /**
     *
     * @param $request
     * @return response
     */
    public function login($request)
    {
        try{
            $user = $this->findUserByIdentifier($request->get('identifier'));
            if(!$user){
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.username.incorrect'));
                $this->utileService->setResponseFrom(UtileService::FROM_SQL);
                return $this->utileService->getResponse();
            }

            $mPassword = $this->findPasswordByUserInternalId($user->getInternalId());
            if (password_verify($request->get('password'), $mPassword['password'])) {
                $this->utileService->setResponseState(true);
                $this->utileService->setResponseData($user);
            } else {
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.password.incorrect'));
            }

            return $this->utileService->getResponse();
        
        } catch (\Exception $e) {
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->getResponse();
        }
    }

    public function forgetPassword($request)
    {
        try{
            $user = $this->findUserByIdentifier($request->get('identifier'));
            if ($user) {
                if (count($user->getEmail()) > 0) {
                    $this->utileService->setResponseState(true);
                    $this->utileService->setResponseData(array());
                    $this->utileService->setResponseMessage($this->translator->trans('user.email.forget.sent'));
                } else {
                    $indication = $this->findPasswordIndicationByIdentifier($request->get('identifier'));
                    $this->utileService->setResponseData($indication);
                    $this->utileService->setResponseState(true);
                }
            } else {
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseData(array());
                $this->utileService->setResponseMessage($this->translator->trans('user.identifier.not.exist'));
            }
            return $this->utileService->getResponse();
        } catch (\Exception $e) {
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->getResponse();
        }
    }

    public function resetPassword($password, $password1, $password2, $internal_token, $internal_id)
    {
        try {
            $user = $this->findUserByInternalToken($internal_token);
            if (!$user) {
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.internal_token.wrong'));
                return $this->utileService->getResponse();
            }
            
            if($user->getInternalId() !== $internal_id){
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.internal_id.wrong'));
                return $this->utileService->getResponse();
            }

            $Mpassword = $this->findPasswordByUserInternalId($user->getInternalId());
            if (!password_verify($password, $Mpassword['password'])) {
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.password.old_password.wrong'));
                return $this->utileService->getResponse();
            }

            if ($password1 === $password) {
                $this->utileService->setResponsePath(array('field' => array('password1', 'password')));
                $this->utileService->setResponseMessage($this->translator->trans('user.password.old_password.same'));
                $this->utileService->setResponseState(false);
                return $this->utileService->getResponse();
            }

            if ($password1 !== $password2) {
                $this->utileService->setResponsePath(array('field' => array('password1', 'password2')));
                $this->utileService->setResponseMessage($this->translator->trans('user.password.differ'));
                $this->utileService->setResponseState(false);
                return $this->utileService->getResponse();
            }
            $this->mPassword = new Mpassword();
            $encodedPassword = $this->encryptPassword($password1);

            $this->mPassword->setPassword($password1);
            $this->mPassword->setEncryptionMethod(self::ENCRYPTION_METHOD);
            $this->mPassword->setIndication($this->preparePasswordIndication($password1));
            $this->mPassword->setSalt(null);
            $this->mPassword->setInternalId($this->prepareInternalId());
            //$this->mPassword->setCreated(new \DateTime('now'));
            //$this->mPassword->setUpdated(new \DateTime('now'));
            $this->mPassword->setUser($user);

            $errorsPassword = $this->validator->validate($this->mPassword);
            if (count($errorsPassword) > 0) {
                $message = $this->translator->trans(
                    $errorsPassword[0]->getMessage(),
                    array(),
                    'validators'
                );
                $this->utileService->setResponsePath(array('field' => $errorsPassword[0]->getPropertyPath()));
                $this->utileService->setResponseMessage($message);
                $this->utileService->setResponseState(false);
                return $this->utileService->getResponse();
            }

            $this->mPassword->setPassword($encodedPassword);
            $this->em->persist($this->mPassword);
            $this->em->flush();

            $this->refreshAlltokensForUser($user->getInternalId(), $user->getInternalToken());
        } catch (\Exception $e) {
            $this->utileService->setResponseData(array());
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->getResponse();
        }

        $this->utileService->setResponseData(array());
        $this->utileService->setResponseState(true);
        $this->utileService->setResponseMessage($this->translator->trans('user.password.changed'));
        return $this->utileService->getResponse();
    }


    public function resetEmail($email, $password, $internal_token, $internal_id)
    {
        try {
            $user = $this->findUserByInternalToken($internal_token);
            if (!$user) {
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.internal_token.wrong'));
                return $this->utileService->getResponse();
            }
            
            if ($user->getInternalId() !== $internal_id) {
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.internal_id.wrong'));
                return $this->utileService->getResponse();
            }

            $Mpassword = $this->findPasswordByUserInternalId($user->getInternalId());
            if (!password_verify($password, $Mpassword['password'])) {
                $this->utileService->setResponseData(array());
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.password.wrong'));
                return $this->utileService->getResponse();
            }

            if (!$this->validateEmailFormat($email)) {
                $this->utileService->setResponsePath(array('field' => 'email'));
                $this->utileService->setResponseMessage($this->translator->trans('user.email.format.invalid'));
                $this->utileService->setResponseState(false);
                return $this->utileService->getResponse();
            }

            if ($user->getEmail() === $email) {
                $this->utileService->setResponsePath(array('field' => array('email')));
                $this->utileService->setResponseMessage($this->translator->trans('user.email.old_email.same'));
                $this->utileService->setResponseState(false);
                return $this->utileService->getResponse();
            }

            if ($this->findUserByEmail($email)) {
                $this->utileService->setResponsePath(array('field' => 'email'));
                $this->utileService->setResponseMessage($this->translator->trans('user.email.exist'));
                $this->utileService->setResponseState(false);
                return $this->utileService->getResponse();
            }
            $user->setEmail($email);
            //$user->setUpdated(new \DateTime('now'));

            $this->em->persist($user);
            $this->em->flush();

        } catch (\Exception $e) {
            $this->utileService->setResponseData(array());
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->getResponse();
        }
        $this->utileService->setResponseData(array());
        $this->utileService->setResponseState(true);
        $this->utileService->setResponseMessage($this->translator->trans('user.email.changed'));
        return $this->utileService->getResponse();
    }

    public function resetTelephone($telephone, $password, $internal_token, $internal_id)
    {
        try{
            $user = $this->findUserByInternalToken($internal_token);
            if (!$user) {
                $this->utileService->setResponseData(array());
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.internal_token.wrong'));
                return $this->utileService->getResponse();
            }
            
            if ($user->getInternalId() !== $internal_id) {
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.internal_id.wrong'));
                return $this->utileService->getResponse();
            }

            $Mpassword = $this->findPasswordByUserInternalId($user->getInternalId());
            if (!password_verify($password, $Mpassword['password'])) {
                $this->utileService->setResponseData(array());
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.password.wrong'));
                return $this->utileService->getResponse();
            }

            if ($user->getTelephone() === $telephone) {
                $this->utileService->setResponsePath(array('field' => array('telephone')));
                $this->utileService->setResponseMessage($this->translator->trans('user.telephone.old.same'));
                $this->utileService->setResponseState(false);
                return $this->utileService->getResponse();
            }

            $errorsUser = $this->validator->validate($user);
            if (count($errorsUser) > 0) {
                $message = $this->translator->trans(
                    $errorsUser[0]->getMessage(),
                    array(),
                    'validators'
                );
                $this->utileService->setResponsePath(array('field' => $errorsUser[0]->getPropertyPath()));
                $this->utileService->setResponseMessage($message);
                $this->utileService->setResponseState(false);
                return $this->utileService->getResponse();
            }

            $user->setTelephone($telephone);
            //$user->setUpdated(new \DateTime('now'));

            $this->em->persist($user);
            $this->em->flush();

        } catch (\Exception $e) {
            $this->utileService->setResponseData(array());
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->getResponse();
        }
        $this->utileService->setResponseData(array());
        $this->utileService->setResponseState(true);
        $this->utileService->setResponseMessage($this->translator->trans('user.telephone.changed'));
        return $this->utileService->getResponse();
    }
    
    public function updateUserInfo($request)
    {
        try{
            /**
             * @var Muser
             */
            $user = $this->findUserByInternalToken($request->headers->get('internal_token'));
            if(!$user){
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.internal_token.wrong'));
                return $this->utileService->getResponse();
            }
            
            if ($user->getInternalId() !== $request->headers->get('internal_id')) {
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.internal_id.wrong'));
                return $this->utileService->getResponse();
            }

            /**
             * timezone, country, location
             */
            if($request->get('timezone') !== NULL){
                $user->setTimezone($request->get('timezone'));
            }
            if($request->get('country') !== NULL){
                $user->setCountry($request->get('country'));
            }
            if($request->get('city') !== NULL){
                $user->setCity($request->get('city'));
            }
            if($request->get('post_number') !== NULL && $request->get('post_number') !== ""){
                $user->setPostNumber((int)$request->get('post_number'));
            } else {
                $user->setPostNumber(NULL);
            }
            if($request->get('country_id') !== NULL && $request->get('country_id') !== ""){
                $user->setCountryId((int)$request->get('country_id'));
            } else {
                $user->setCountryId(NULL);
            }
            if($request->get('location_id') !== NULL && $request->get('location_id') !== ""){
                $user->setLocationId((int)$request->get('location_id'));
            } else {
                $user->setLocationId(NULL);
            }
            /*
             * end of location
             */
            if ($request->get('is_zh') == 1) {
                $user->setIsZh(1);
            } else {
                $user->setIsZh(0);
            }
            
            if ($request->get('is_fr') == 1) {
                $user->setIsFr(1);
            } else {
                $user->setIsFr(0);
            }
            
            if ($request->get('is_en') == 1) {
                $user->setIsEn(1);
            } else {
                $user->setIsEn(0);
            }
            
            if($request->get('website') !== NULL){
                $user->setWebsite($request->get('website'));
            }
            if($request->get('description') !== NULL){
                $user->setDescription($request->get('description'));
            }
            if($request->get('translated_description') !== NULL){
                $user->setTranslatedDescription($request->get('translated_description'));
            }
            if($request->get('is_single') == 1){
                if($request->get('nickname') !== NULL){
                    $user->setNickname($request->get('nickname'));
                }
                if($request->get('wechat') !== NULL){
                    $user->setWechat($request->get('wechat'));
                }
                if($request->get('facebook') !== NULL){
                    $user->setFacebook($request->get('facebook'));
                }
                if($request->get('instagram') !== NULL){
                    $user->setInstagram($request->get('instagram'));
                }
                if($request->get('skin_color') !== NULL){
                    $user->setSkinColor($request->get('skin_color'));
                }
                if($request->get('weight') !== NULL){
                    $user->setWeight($request->get('weight'));
                }
                if($request->get('height') !== NULL){
                    $user->setHeight($request->get('height'));
                }
                
                if($request->get('birthday') !== NULL && $request->get('birthday') !==""){
                    $time = strtotime($request->get('birthday'));
                    $time = date('Y-m-d',$time);
                    $time = new \DateTime($time);
                    $user->setBirthday($time);
                } else {
                    $user->setBirthday(null);
                }
                
                
                if($request->get('hour_price') !== NULL && $request->get('hour_price')!== ""){
                    $user->setHourPrice($request->get('hour_price'));
                } else {
                    $user->setHourPrice(NULL);
                }
                if($request->get('hour_price_unit') !== NULL){
                    $user->setHourPriceUnit($request->get('hour_price_unit'));
                } 
                if($request->get('night_price') !== NULL && $request->get('night_price')!== ""){
                    $user->setNightPrice($request->get('night_price'));
                } else {
                    $user->setNightPrice(NULL);
                }
                if($request->get('night_price_unit') !== NULL){
                    $user->setNightPriceUnit($request->get('night_price_unit'));
                }
            } else {
                if($request->get('shop_address') !== NULL){
                    $user->setShopAddress($request->get('shop_address'));
                }

                // set shop name
            }

            $this->em->persist($user);
            $this->em->flush();
            
        } catch (\Exception $e) {
            $this->utileService->setResponseData(array());
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->getResponse();
        }
        $this->utileService->setResponseData(array());
        $this->utileService->setResponseState(true);
        $this->utileService->setResponseMessage($this->translator->trans('user.information.updated'));
        return $this->utileService->getResponse();
    }        
    
    public function sendNewUserMail($internal_id, $internal_token)
    {
        try{
            $user = $this->findUserByInternalId($internal_id);
            if(!$user){
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.internal_id.not.exist'));
                return $this->utileService->getResponse();
            }

            if($user->getInternalToken() !== $internal_token){
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.internal_token.wrong'));
                return $this->utileService->getResponse();
            }

            if(count($user->getEmail()) === 0){
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.email.empty'));
                return $this->utileService->getResponse();
            }

            $password = $this->findPasswordIndicationByIdentifier($user->getUsername());
            $data['username'] = $user->getUsername();
            $data['email'] = $user->getEmail();
            $data['telephone'] = $user->getTelephone();
            $data['indication'] = $password['indication'];
            $data['created'] = $user->getCreated()->format('Y-m-d H:i:s');
            $this->mailer->sendNewUserMail($user->getEmail(), $this->container->getParameter('service_mail'), $this->container->getParameter('cc_mail'), $this->translator->trans('messages.email.title.newUser'), $data);

            $this->utileService->setResponseState(true);
            $this->utileService->setResponseMessage($this->translator->trans('user.email.sent'));
            return $this->utileService->getResponse();
        } catch (\Exception $e) {
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->getResponse();
        }
    }
    
    public function sendPasswordChangedMail($internal_id, $internal_token)
    {
        try{
            $user = $this->findUserByInternalId($internal_id);
            if(!$user){
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.internal_id.not.exist'));
                return $this->utileService->getResponse();
            }

            if($user->getInternalToken() !== $internal_token){
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.internal_token.wrong'));
                return $this->utileService->getResponse();
            }

            if(count($user->getEmail()) === 0){
                $this->utileService->setResponseData(array());
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.email.empty'));
                return $this->utileService->getResponse();
            }

            $password = $this->findPasswordIndicationByIdentifier($user->getUsername());
            $data['username'] = $user->getUsername();
            $data['email'] = $user->getEmail();
            $data['telephone'] = $user->getTelephone();
            $data['indication'] = $password['indication'];
            $data['updated'] = $user->getUpdated()->format('Y-m-d H:i:s');
            $this->mailer->sendPasswordChangedMail($user->getEmail(), $this->container->getParameter('service_mail'), $this->container->getParameter('cc_mail'), $this->translator->trans('messages.email.title.passwordChanged'), $data);

            $this->utileService->setResponseState(true);
            $this->utileService->setResponseMessage($this->translator->trans('user.email.sent'));
            return $this->utileService->getResponse();
        } catch (\Exception $e) {
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->getResponse();
        }
    }   
    
    public function sendPasswordForgetMail($identifier)
    {
        try{
            $user = $this->findUserByIdentifier($identifier);
            if(!$user){
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.identifier.not.exist', array('%identifier%' => $identifier)));
                return $this->utileService->getResponse();
            }

            if(count($user->getEmail()) === 0){
                $password = $this->findPasswordIndicationByIdentifier($user->getUsername());
                $this->utileService->setResponseData($password);
                $this->utileService->setResponseState(true);
                $this->utileService->setResponseMessage($this->translator->trans('user.email.empty'));
                return $this->utileService->getResponse();
            }

            $password = $this->findPasswordIndicationByIdentifier($user->getUsername());
            $data['username'] = $user->getUsername();
            $data['email'] = $user->getEmail();
            $data['telephone'] = $user->getTelephone();
            $data['indication'] = $password['indication'];
            $data['updated'] = $user->getUpdated()->format('Y-m-d H:i:s');
            $this->mailer->sendPasswordForgetMail($user->getEmail(), $this->container->getParameter('service_mail'), $this->container->getParameter('cc_mail'), $this->translator->trans('messages.email.title.passwordForget'), $data);

            $this->utileService->setResponseState(true);
            $this->utileService->setResponseMessage($this->translator->trans('user.email.forget.sent', array('%email%' => $user->getEmail())));
            return $this->utileService->getResponse();
        } catch (\Exception $e) {
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->getResponse();
        }
    }

    public function getSingleUserPageByUsername($username)
    {
        try{
            $this->utileService->setResponseFrom(UtileService::FROM_CACHE);
            /*
             * user
             */
            $user = $this->cacheService->getSingleUserByUsernameCache($username);

            if(!$user ){
                $user = $this->findUserByUsername($username);
                $this->utileService->setResponseFrom(UtileService::FROM_SQL);

                if(!$user){
                    $this->utileService->setResponseState(false);
                    $this->utileService->setResponseMessage($this->translator->trans('user.username.incorrect'));
                    return $this->utileService->getResponse();
                }

                $this->cacheService->setSingleUserByUsernameCache($username, serialize($user));
            } else {
                $user = unserialize($user);
            }
            
            /*
             * user photos
             */
            $photos = $this->cacheService->getSingleUserPhotosByUserIdCache($user->getId());
            
            if(!$photos){
                $photos = $this->findUserPhotosByUserId($user->getId());
                $this->utileService->setResponseFrom(UtileService::FROM_SQL);
                
                $this->cacheService->setSingleUserPhotosByUserIdCache($user->getId(), serialize($photos));
            } else {
                $photos = unserialize($photos);
            }
            
            /*
             * profile photo
             */
            $profile_photo = $this->cacheService->getProfilePhotoByUserIdCache($user->getId());
            
            if(!$profile_photo){
                $profile_photo = $this->findProfilePhotoByUserId($user->getId());
                $this->utileService->setResponseFrom(UtileService::FROM_SQL);
                
                $this->cacheService->setProfilePhotoByUserIdCache($user->getId(), serialize($profile_photo));
            } else {
                $profile_photo = unserialize($profile_photo);
            }
            

            $this->utileService->setResponseState(true);
            $data = array(UtileService::DATA_STRUCTURE_USER => $user, UtileService::DATA_STRUCTURE_USER_PHOTOS => $photos, UtileService::DATA_STRUCTURE_PROFILE_PHOTO => $profile_photo);
            $this->utileService->setResponseData($data);
            return $this->utileService->getResponse();
        } catch (\Exception $e) {
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->getResponse();
        }
    }
    
    public function getSingleShopPageByUsername($username)
    {
        $userData = $this->getSingleUserPageByUsername($username);
        // add shop hours
        // add shop price details
        return $userData;
    }     
    
    // push user to the top of list 
    public function topUser($internal_id, $internal_token)
    {
        try{
            $user = $this->findUserByInternalId($internal_id);
            if(!$user){
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.internal_id.not.exist'));
                return $this->utileService->getResponse();
            }

            if($user->getInternalToken() !== $internal_token){
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.internal_token.wrong'));
                return $this->utileService->getResponse();
            }
            
            $time_passed = (new \DateTime('now'))->getTimestamp() - $user->getTopTime()->getTimestamp();
            if($time_passed < (int)$this->minTopTime){
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.toptime.too.fast'));
                return $this->utileService->getResponse();
            }
            $user->setTopTime(new \DateTime('now'));
            $this->em->persist($user);
            $this->em->flush();
            
            $this->utileService->setResponseState(true);
            $this->utileService->setResponseData($user);
            $this->utileService->setResponseMessage($this->translator->trans('user.toptime.updated'));
            return $this->utileService->getResponse();
            
        } catch (\Exception $e) {
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->getResponse();
        }
    }        
    
    public function deleteUser($internal_id, $internal_token)
    {
        try{
            $user = $this->findUserByInternalId($internal_id);
            if(!$user){
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.internal_id.not.exist'));
                return $this->utileService->getResponse();
            }

            if($user->getInternalToken() !== $internal_token){
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.internal_token.wrong'));
                return $this->utileService->getResponse();
            }

            $user->setIsDeleted(UtileService::TINY_INT_TRUE);
            $user->setIsActive(UtileService::TINY_INT_FALSE);
            $this->em->persist($user);
            $this->em->flush();
            
            $this->utileService->setResponseState(true);
            $this->utileService->setResponseData($user);
            $this->utileService->setResponseMessage($this->translator->trans('user.deleted'));
            return $this->utileService->getResponse();
        } catch (\Exception $e) {
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->getResponse();
        }
    }
    
    public function enableUser($internal_id, $internal_token)
    {
        try{
            $user = $this->findUserByInternalId($internal_id);
            if(!$user){
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.internal_id.not.exist'));
                return $this->utileService->getResponse();
            }

            if($user->getInternalToken() !== $internal_token){
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.internal_token.wrong'));
                return $this->utileService->getResponse();
            }

            $user->setIsDeleted(UtileService::TINY_INT_FALSE);
            $user->setIsActive(UtileService::TINY_INT_TRUE);
            $this->em->persist($user);
            $this->em->flush();
            
            $this->utileService->setResponseState(true);
            $this->utileService->setResponseData($user);
            $this->utileService->setResponseMessage($this->translator->trans('user.enabled'));
            return $this->utileService->getResponse();
        } catch (\Exception $e) {
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->getResponse();
        }
    }
}
