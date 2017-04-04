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
     *
     * @var UtileService
     */
    protected $utileService;


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

    public function __construct(Registry $doctrine, Session $session, Translator $translator, RecursiveValidator $validator, UtileService $utileService)
    {
        $this->doctrine = $doctrine;
        $this->em = $this->doctrine->getManager();
        $this->session = $session;
        $this->translator = $translator;
        $this->validator = $validator;
        $this->utileService = $utileService;
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
        $user = $this->findUserByIdentifier($request->get('identifier'));
        if ($user) {
            $mPassword = $this->findPasswordByUserInternalId($user->getInternalId());
            if (password_verify($request->get('password'), $mPassword['password'])) {
                $this->utileService->setResponseState(true);
                $this->utileService->setResponseData($user);
                $this->utileService->setResponseMessage(null);
            } else {
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseData(array());
                $this->utileService->setResponseMessage('user.password.incorrect');
            }
        } else {
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseData(array());
            $this->utileService->setResponseMessage('user.username.incorrect');
        }
        return $this->utileService->response;
    }

    public function forgetPassword($request)
    {
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
            $this->utileService->setResponseMessage('user.identifier.not_exist');
        }
        return $this->utileService->response;
    }

    public function resetPassword($request)
    {
        try {
            $user = $this->findUserByInternalToken($request->get('internal_token'));
            if (!$user) {
                $this->utileService->setResponseData(array());
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage('user.token.wrong');
                return $this->utileService->response;
            }

            $password = $this->findPasswordByUserInternalId($user->getInternalId());
            if (!password_verify($request->get('password'), $password['password'])) {
                $this->utileService->setResponseData(array());
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage('user.password.old_password.wrong');
                return $this->utileService->response;
            }

            if ($request->get('password1') === $request->get('password')) {
                $this->utileService->setResponsePath(array('field' => array('password1', 'password')));
                $this->utileService->setResponseMessage('user.password.old_password.same');
                $this->utileService->setResponseState(false);
                return $this->utileService->response;
            }

            if ($request->get('password1') !== $request->get('password2')) {
                $this->utileService->setResponsePath(array('field' => array('password1', 'password2')));
                $this->utileService->setResponseMessage('user.password.differ');
                $this->utileService->setResponseState(false);
                return $this->utileService->response;
            }
            $this->mPassword = new Mpassword();
            $encodedPassword = $this->encryptPassword($request->get('password1'));

            $this->mPassword->setPassword($request->get('password1'));
            $this->mPassword->setEncryptionMethod('bcrypt');
            $this->mPassword->setIndication($this->preparePasswordIndication($request->get('password1')));
            $this->mPassword->setSalt(null);
            $this->mPassword->setInternalId($this->prepareInternalId());
            $this->mPassword->setCreated(new \DateTime('now'));
            $this->mPassword->setUpdated(new \DateTime('now'));
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
            $user->setUpdated(new \DateTime('now'));

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
            $user->setUpdated(new \DateTime('now'));

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
}
