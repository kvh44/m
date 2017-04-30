<?php
namespace ApiBundle\Services;

use ApiBundle\Entity\Muser;
use ApiBundle\Entity\Mpassword;
use ApiBundle\Services\UtileService;
use ApiBundle\Services\CacheService;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ApiBundle\Services\Mailer;
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
    
    
    const MIN_LENGTH_PASSWORD = 8;
    const MIN_LENGTH_TOKEN = 32;

    public function __construct(Registry $doctrine, Session $session, Translator $translator, RecursiveValidator $validator, Container $container,UtileService $utileService, Mailer $mailer, CacheService $cacheService)
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
    }

    public function createUser($request)
    {
        try {
            $this->mUser = new Muser();
            $this->mPassword = new Mpassword();

            if (strlen($request->get('email')) > 0) {
                if (!$this->validateEmailFormat($request->get('email'))) {
                    $this->utileService->setResponsePath(array('field' => 'email'));
                    $this->utileService->setResponseMessage('user.email.format.invalid');
                    $this->utileService->setResponseState(false);
                    return $this->utileService->response;
                }
                if ($this->findUserByEmail($request->get('email'))) {
                    $this->utileService->setResponsePath(array('field' => 'email'));
                    $this->utileService->setResponseMessage('user.email.exist');
                    $this->utileService->setResponseState(false);
                    return $this->utileService->response;
                }
            }

            if ($user = $this->findUserByTelephone($request->get('telephone'))) {
                $this->utileService->setResponseData($user);
                $this->utileService->setResponseMessage('user.telephone.exist');
                $this->utileService->setResponseState(false);
                return $this->utileService->response;
            }


            if ($user = $this->findUserByUsername($request->get('username'))) {
                $this->utileService->setResponseData($user);
                $this->utileService->setResponseMessage('user.username.exist');
                $this->utileService->setResponseState(false);
                return $this->utileService->response;
            }

            if ($request->get('password1') !== $request->get('password2')) {
                $this->utileService->setResponsePath(array('field' => array('password1', 'password2')));
                $this->utileService->setResponseMessage('user.password.differ');
                $this->utileService->setResponseState(false);
                return $this->utileService->response;
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
                if ($request->get('is_shop') === 1) {
                    $this->mUser->setIsShop(1);
                    $this->mUser->setIsSingle(0);
                }
                if ($request->get('is_zh')) {
                    $this->mUser->setIsZh(1);
                }
                if ($request->get('is_fr')) {
                    $this->mUser->setIsFr(1);
                }
                if ($request->get('is_en')) {
                    $this->mUser->setIsEn(1);
                }
                if ($request->get('country_id')) {
                    $this->mUser->setCountryId($request->get('country_id'));
                }
                //$this->mUser->setCreated(new \DateTime('now'));
                //$this->mUser->setUpdated(new \DateTime('now'));


                $encodedPassword = $this->encryptPassword($request->get('password1'));
                $this->mPassword->setPassword($request->get('password1'));
                $this->mPassword->setEncryptionMethod('bcrypt');
                $this->mPassword->setIndication($this->preparePasswordIndication($request->get('password1')));
                $this->mPassword->setSalt(null);
                $this->mPassword->setInternalId($this->prepareInternalId());
                //$this->mPassword->setCreated(new \DateTime('now'));
                //$this->mPassword->setUpdated(new \DateTime('now'));
                $this->mPassword->setUser($this->mUser);


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
                    return $this->utileService->response;
                }

                $errorsUser = $this->validator->validate($this->mUser);
                if (count($errorsUser) > 0) {
                    $message = $this->translator->trans(
                        $errorsUser[0]->getMessage(),
                        array(),
                        'validators'
                    );
                    $this->utileService->setResponsePath(array('field' => $errorsUser[0]->getPropertyPath()));
                    $this->utileService->setResponseMessage($message);
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
                //$this->mailer->sendNewUserMail();
            }

            return $this->utileService->response;
        } catch (\Exception $e) {
            $this->utileService->setResponseMessage($e->getMessage());
            $this->utileService->setResponseState(false);
            return $this->utileService->response;
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

    public function encryptPassword($password, $algo = 'bcrypt', $salt = null)
    {
        return password_hash($password, PASSWORD_BCRYPT);
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

    public function findUserByInternalToken($internal_token)
    {
        return $this->em->getRepository('ApiBundle:Muser')->loadUserByInternalToken($internal_token);
    }
    
    public function findPhotosByUserId($user_id, $type = null)
    {
        return $this->em->getRepository('ApiBundle:Mphoto')->loadPhotosByUserId($user_id, $type);
    }

    private function refreshAlltokensForUser($internalId, $internalToken)
    {
        try {
            $user = $this->findUserByInternalToken($internalToken);
            if (!$user) {
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseData(array());
                $this->utileService->setResponseMessage('user.token.wrong');
            }

            if ($user->getInternalId() === $internalId) {
                $user->setToken($this->prepareToken());
                $user->setInternalToken($this->prepareInternalToken());
                $user->setExternalToken($this->prepareExternalToken());
                $this->em->persist($user);
                $this->em->flush();

                $this->utileService->setResponseState(true);
                $this->utileService->setResponseData(array());
                $this->utileService->setResponseMessage('user.token.changed');
            } else {
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseData(array());
                $this->utileService->setResponseMessage('user.token.wrong');
            }
        } catch (\Exception $e) {
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseData(array());
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->response;
        }
        return $this->utileService->response;

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
                $this->utileService->setResponseMessage('user.username.incorrect');
                $this->utileService->setResponseFrom(UtileService::FROM_SQL);
                return $this->utileService->response;
            }

            $mPassword = $this->findPasswordByUserInternalId($user->getInternalId());
            if (password_verify($request->get('password'), $mPassword['password'])) {
                $this->utileService->setResponseState(true);
                $this->utileService->setResponseData($user);
            } else {
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage('user.password.incorrect');
            }

            return $this->utileService->response;
        
        } catch (\Exception $e) {
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->response;
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
                    $this->utileService->setResponseMessage('user.email.indication_change_password.will_be_sent');
                } else {
                    $indication = $this->findPasswordIndicationByIdentifier($request->get('identifier'));
                    $this->utileService->setResponseData($indication);
                    $this->utileService->setResponseState(true);
                }
            } else {
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseData(array());
                $this->utileService->setResponseMessage('user.identifier.not.exist');
            }
            return $this->utileService->response;
        } catch (\Exception $e) {
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->response;
        }
    }

    public function resetPassword($password, $password1, $password2, $internal_token)
    {
        try {
            $user = $this->findUserByInternalToken($internal_token);
            if (!$user) {
                $this->utileService->setResponseData(array());
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage('user.token.wrong');
                return $this->utileService->response;
            }

            $Mpassword = $this->findPasswordByUserInternalId($user->getInternalId());
            if (!password_verify($password, $Mpassword['password'])) {
                $this->utileService->setResponseData(array());
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage('user.password.old_password.wrong');
                return $this->utileService->response;
            }

            if ($password1 === $password) {
                $this->utileService->setResponsePath(array('field' => array('password1', 'password')));
                $this->utileService->setResponseMessage('user.password.old_password.same');
                $this->utileService->setResponseState(false);
                return $this->utileService->response;
            }

            if ($password1 !== $password2) {
                $this->utileService->setResponsePath(array('field' => array('password1', 'password2')));
                $this->utileService->setResponseMessage('user.password.differ');
                $this->utileService->setResponseState(false);
                return $this->utileService->response;
            }
            $this->mPassword = new Mpassword();
            $encodedPassword = $this->encryptPassword($password1);

            $this->mPassword->setPassword($password1);
            $this->mPassword->setEncryptionMethod('bcrypt');
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
                return $this->utileService->response;
            }

            $this->mPassword->setPassword($encodedPassword);
            $this->em->persist($this->mPassword);
            $this->em->flush();

            $this->refreshAlltokensForUser($user->getInternalId(), $user->getInternalToken());
        } catch (\Exception $e) {
            $this->utileService->setResponseData(array());
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->response;
        }

        $this->utileService->setResponseData(array());
        $this->utileService->setResponseState(true);
        $this->utileService->setResponseMessage('user.password.changed');
        return $this->utileService->response;
    }


    public function resetEmail($email, $password, $internal_token)
    {
        try {
            $user = $this->findUserByInternalToken($internal_token);
            if (!$user) {
                $this->utileService->setResponseData(array());
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage('user.token.wrong');
                return $this->utileService->response;
            }

            $Mpassword = $this->findPasswordByUserInternalId($user->getInternalId());
            if (!password_verify($password, $Mpassword['password'])) {
                $this->utileService->setResponseData(array());
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage('user.password.wrong');
                return $this->utileService->response;
            }

            if (!$this->validateEmailFormat($email)) {
                $this->utileService->setResponsePath(array('field' => 'email'));
                $this->utileService->setResponseMessage('user.email.format.invalid');
                $this->utileService->setResponseState(false);
                return $this->utileService->response;
            }

            if ($user->getEmail() === $email) {
                $this->utileService->setResponsePath(array('field' => array('email')));
                $this->utileService->setResponseMessage('user.email.old_email.same');
                $this->utileService->setResponseState(false);
                return $this->utileService->response;
            }

            if ($this->findUserByEmail($email)) {
                $this->utileService->setResponsePath(array('field' => 'email'));
                $this->utileService->setResponseMessage('user.email.exist');
                $this->utileService->setResponseState(false);
                return $this->utileService->response;
            }
            $user->setEmail($email);
            //$user->setUpdated(new \DateTime('now'));

            $this->em->persist($user);
            $this->em->flush();

        } catch (\Exception $e) {
            $this->utileService->setResponseData(array());
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->response;
        }
        $this->utileService->setResponseData(array());
        $this->utileService->setResponseState(true);
        $this->utileService->setResponseMessage('user.email.changed');
        return $this->utileService->response;
    }

    public function resetTelephone($telephone, $password, $internal_token)
    {
        try{
            $user = $this->findUserByInternalToken($internal_token);
            if (!$user) {
                $this->utileService->setResponseData(array());
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage('user.token.wrong');
                return $this->utileService->response;
            }

            $Mpassword = $this->findPasswordByUserInternalId($user->getInternalId());
            if (!password_verify($password, $Mpassword['password'])) {
                $this->utileService->setResponseData(array());
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage('user.password.wrong');
                return $this->utileService->response;
            }

            if ($user->getTelephone() === $telephone) {
                $this->utileService->setResponsePath(array('field' => array('telephone')));
                $this->utileService->setResponseMessage('user.telephone.old_telephone.same');
                $this->utileService->setResponseState(false);
                return $this->utileService->response;
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
                return $this->utileService->response;
            }

            $user->setTelephone($telephone);
            //$user->setUpdated(new \DateTime('now'));

            $this->em->persist($user);
            $this->em->flush();

        } catch (\Exception $e) {
            $this->utileService->setResponseData(array());
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->response;
        }
        $this->utileService->setResponseData(array());
        $this->utileService->setResponseState(true);
        $this->utileService->setResponseMessage('user.telephone.changed');
        return $this->utileService->response;
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
                $this->utileService->setResponseMessage('user.token.wrong');
                return $this->utileService->response;
            }

            /**
             * timezone, country, location
             */
            if(strlen($request->get('timezone')) > 0){
                $user->setTimezone($request->get('timezone'));
            }
            if(strlen($request->get('country')) > 0){
                $user->setCountry($request->get('country'));
            }
            if(strlen($request->get('city')) > 0){
                $user->setCity($request->get('city'));
            }
            if(strlen($request->get('post_number')) > 0){
                $user->setPostNumber($request->get('post_number'));
            }
            if(strlen($request->get('country_id')) > 0){
                $user->setCountryId((int)$request->get('country_id'));
            }
            if(strlen($request->get('location_id')) > 0){
                $user->setLocationId((int)$request->get('location_id'));
            }
            /*
             * end of location
             */
            if($request->get('website')){
                $user->setWebsite($request->get('website'));
            }
            if($request->get('description')){
                $user->setDescription($request->get('description'));
            }
            if($request->get('translated_description')){
                $user->setTranslatedDescription($request->get('translated_description'));
            }
            if($request->get('is_single') == 1){
                if($request->get('nickname')){
                    $user->setNickname($request->get('nickname'));
                }
                if($request->get('wechat')){
                    $user->setWechat($request->get('wechat'));
                }
                if($request->get('facebook')){
                    $user->setFacebook($request->get('facebook'));
                }
                if($request->get('instagram')){
                    $user->setInstagram($request->get('instagram'));
                }
                if($request->get('skin_color')){
                    $user->setSkinColor($request->get('skin_color'));
                }
                if($request->get('weight')){
                    $user->setWeight($request->get('weight'));
                }
                if($request->get('height')){
                    $user->setHeight($request->get('height'));
                }
                if($request->get('birthday')){
                    $user->setBirthday($request->get('birthday'));
                }
                if($request->get('hour_price')){
                    $user->setHourPrice($request->get('hour_price'));
                }
                if($request->get('hour_price_unit')){
                    $user->setHourPriceUnit($request->get('hour_price_unit'));
                }
                if($request->get('night_price')){
                    $user->setNightPrice($request->get('night_price'));
                }
                if($request->get('night_price_unit')){
                    $user->setNightPriceUnit($request->get('night_price_unit'));
                }
            } else {
                if($request->get('shop_address')){
                    $user->setShopAddress($request->get('shop_address'));
                }

                // set shop name
            }

            $this->em->persist($user);
            $this->em->flush();
            
        } catch (\Exception $e) {
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->getResponse();
        }
        $this->utileService->setResponseState(true);
        $this->utileService->setResponseMessage('user.information.updated');
        return $this->utileService->getResponse();
    }        
    
    public function sendNewUserMail($internal_id, $internal_token)
    {
        try{
            $user = $this->findUserByInternalId($internal_id);
            if(!$user){
                $this->utileService->setResponseData(array());
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage('user.internal_id.not_exist');
                return $this->utileService->response;
            }

            if($user->getInternalToken() !== $internal_token){
                $this->utileService->setResponseData(array());
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage('user.token.wrong');
                return $this->utileService->response;
            }

            if(count($user->getEmail()) === 0){
                $this->utileService->setResponseData(array());
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage('user.email.empty');
                return $this->utileService->response;
            }

            $password = $this->findPasswordIndicationByIdentifier($user->getUsername());
            $data['username'] = $user->getUsername();
            $data['email'] = $user->getEmail();
            $data['telephone'] = $user->getTelephone();
            $data['indication'] = $password['indication'];
            $data['created'] = $user->getCreated()->format('Y-m-d H:i:s');
            $this->mailer->sendNewUserMail($user->getEmail(), $this->container->getParameter('service_mail'), $this->container->getParameter('cc_mail'), $this->translator->trans('messages.email.title.newUser'), $data);

            $this->utileService->setResponseState(true);
            $this->utileService->setResponseMessage('user.email.sent');
            return $this->utileService->response;
        } catch (\Exception $e) {
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->response;
        }
    }
    
    public function sendPasswordChangedMail($internal_id, $internal_token)
    {
        try{
            $user = $this->findUserByInternalId($internal_id);
            if(!$user){
                $this->utileService->setResponseData(array());
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage('user.internal_id.not_exist');
                return $this->utileService->response;
            }

            if($user->getInternalToken() !== $internal_token){
                $this->utileService->setResponseData(array());
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage('user.token.wrong');
                return $this->utileService->response;
            }

            if(count($user->getEmail()) === 0){
                $this->utileService->setResponseData(array());
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage('user.email.empty');
                return $this->utileService->response;
            }

            $password = $this->findPasswordIndicationByIdentifier($user->getUsername());
            $data['username'] = $user->getUsername();
            $data['email'] = $user->getEmail();
            $data['telephone'] = $user->getTelephone();
            $data['indication'] = $password['indication'];
            $data['updated'] = $user->getUpdated()->format('Y-m-d H:i:s');
            $this->mailer->sendPasswordChangedMail($user->getEmail(), $this->container->getParameter('service_mail'), $this->container->getParameter('cc_mail'), $this->translator->trans('messages.email.title.passwordChanged'), $data);

            $this->utileService->setResponseState(true);
            $this->utileService->setResponseMessage('user.email.sent');
            return $this->utileService->response;
        } catch (\Exception $e) {
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->response;
        }
    }   
    
    public function sendPasswordForgetMail($identifier)
    {
        try{
            $user = $this->findUserByIdentifier($identifier);
            if(!$user){
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.identifier.not.exist', array('%identifier%' => $identifier)));
                return $this->utileService->response;
            }

            if(count($user->getEmail()) === 0){
                $password = $this->findPasswordIndicationByIdentifier($user->getUsername());
                $this->utileService->setResponseData($password);
                $this->utileService->setResponseState(true);
                $this->utileService->setResponseMessage($this->translator->trans('user.email.empty'));
                return $this->utileService->response;
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
            return $this->utileService->response;
        } catch (\Exception $e) {
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->response;
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
                    $this->utileService->setResponseMessage('user.username.wrong');
                    return $this->utileService->response;
                }

                $this->cacheService->setSingleUserByUsernameCache($username, serialize($user));
            } else {
                $user = unserialize($user);
            }
            
            /*
             * photos
             */
            $photos = $this->cacheService->getSingleUserPhotosByUserIdCache($user->getId());
            
            if(!$photos){
                $photos = $this->findPhotosByUserId($user->getId());
                $this->utileService->setResponseFrom(UtileService::FROM_SQL);
                
                $this->cacheService->setSingleUserPhotosByUserIdCache($user->getId(), serialize($photos));
            } else {
                $photos = unserialize($photos);
            }
            

            $this->utileService->setResponseState(true);
            $data = array('user' => $user, 'photos' => $photos);
            $this->utileService->setResponseData($data);
            return $this->utileService->getResponse();
        } catch (\Exception $e) {
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->getResponse();
        }
    }
    
}
