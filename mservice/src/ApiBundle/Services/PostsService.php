<?php
namespace ApiBundle\Services;

use ApiBundle\Entity\Mpost;
use ApiBundle\Services\UtileService;
use ApiBundle\Services\CacheService;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\DependencyInjection\Container;


class PostsService
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
	
    protected $mPost;

    protected $user;
	
	
	
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
	
    public function findPostById($id)
    {
        return $this->em->getRepository('ApiBundle:Mpost')->loadPostById($id);
    }
	
    public function findPostPhotosByPostId($post_id)
    {
        return $this->em->getRepository('ApiBundle:Mphoto')->loadPhotosByPostId($post_id);
    }
	
    public function findUserByInternalToken($internal_token)
    {
        return $this->em->getRepository('ApiBundle:Muser')->loadUserByInternalToken($internal_token);
    }
	
    public function findPostByInternalId($internal_id)
    {
            return $this->em->getRepository('ApiBundle:Mpost')->loadPostByInternalId($internal_id);
    }
	
    public function prepareInternalId($internalId = '')
    {
        return $internalId . UtileService::RandomString(UtileService::MIN_LENGTH_TOKEN) . UtileService::getDateTimeMicroseconds();
    }
	
    public function prepareSlug($slug)
    {
        return $slug . '-' . UtileService::getDateTimeMicroseconds();
    }
	
    public function getSinglePostPageById($id)
    {
        try{
            $this->utileService->setResponseFrom(UtileService::FROM_CACHE);
            /*
             * post
             */
            $post = $this->cacheService->getSinglePostCache($id);

            if(!$post ){
                $post = $this->findPostById($id);
                $this->utileService->setResponseFrom(UtileService::FROM_SQL);

                if(!$post){
                    $this->utileService->setResponseState(false);
                    $this->utileService->setResponseMessage($this->translator->trans('post.id.invalid'));
                    return $this->utileService->getResponse();
                }

                $this->cacheService->setSinglePostCache($id, serialize($post));
            } else {
                $post = unserialize($post);
            }
            
            /*
             * post photos
             */
            $photos = $this->cacheService->getSinglePostPhotosByPostIdCache($post->getId());
            
            if(!$photos){
                $photos = $this->findPostPhotosByPostId($post->getId());
                $this->utileService->setResponseFrom(UtileService::FROM_SQL);
                
                $this->cacheService->setSinglePostPhotosByPostIdCache($post->getId(), serialize($photos));
            } else {
                $photos = unserialize($photos);
            }
           

            $this->utileService->setResponseState(true);
            $data = array(UtileService::DATA_STRUCTURE_POST => $post, UtileService::DATA_STRUCTURE_POST_PHOTOS => $photos);
            $this->utileService->setResponseData($data);
            return $this->utileService->getResponse();
        } catch (\Exception $e) {
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->getResponse();
        }
    }
		
    public function createPost($request)
    {
        try{
            $this->user = $this->findUserByInternalToken($request->headers->get('internal_token'));
            if(!$this->user){
                $this->utileService->setResponseMessage($this->translator->trans('user.token.wrong'));
                $this->utileService->setResponseState(false);
                return $this->utileService->getResponse();
            }

            if($this->user->getInternalId() !== $request->headers->get('internal_id')) {
                $this->utileService->setResponseMessage($this->translator->trans('user.internal_id.not.exist'));
                $this->utileService->setResponseState(false);
                return $this->utileService->getResponse();
            }



            $this->mPost = new Mpost();

            $this->mPost->setUser($this->user);
            $this->mPost->setTitle($request->get('title'));
            $this->mPost->setDescription($request->get('description'));
            $this->mPost->setDisplayedHome(UtileService::TINY_INT_TRUE);
            $this->mPost->setIsFromOtherWeb($request->get('is_from_other_web'));
            $this->mPost->setOtherWeb($request->get('other_web'));
            $this->mPost->setDisplayedHome(UtileService::TINY_INT_TRUE);
            $this->mPost->setInternalId($this->prepareInternalId());
            $this->mPost->setSlug($this->prepareSlug($this->mPost->getTitle()));

            $errorsPost = $this->validator->validate($this->mPost);
            if (count($errorsPost) > 0) {
                $message = $this->translator->trans(
                        $errorsPost[0]->getMessage()
                );
                $this->utileService->setResponsePath(array('field' => $errorsPost[0]->getPropertyPath()));
                $this->utileService->setResponseMessage($message);
                $this->utileService->setResponseState(false);
                return $this->utileService->getResponse();
            }

            $this->em->persist($this->mPost);
            $this->em->flush();
            $this->utileService->setResponseData($this->mPost);
            $this->utileService->setResponseFrom(UtileService::FROM_SQL);
            return $this->utileService->getResponse();
            
            } catch (\Exception $e) {
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($e->getMessage());
                return $this->utileService->getResponse();
            }
    }
	
    public function updatePost($request)
    {
        try{
            $user = $this->findUserByInternalToken($request->headers->get('internal_token'));
            if(!$user){
                    $this->utileService->setResponseMessage($this->translator->trans('user.internal_token.wrong'));
                    $this->utileService->setResponseState(false);
                    return $this->utileService->getResponse();
            }

            if($user->getInternalId() !== $request->headers->get('internal_id')) {
                    $this->utileService->setResponseMessage($this->translator->trans('user.internal_id.not.exist'));
                    $this->utileService->setResponseState(false);
                    return $this->utileService->getResponse();
            }

            $this->mPost = $this->findPostByInternalId($request->headers->get('internal_id_post'));
            if(!$this->mPost){
                    $this->utileService->setResponseState(false);
                    $this->utileService->setResponseMessage($this->translator->trans('post.not.exist'));
                    return $this->utileService->getResponse();
            }

            if($this->mPost->getUser()->getId() !== $user->getId()){
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('post.not.yours'));
                return $this->utileService->getResponse();
            }
            $this->mPost->setTitle($request->get('title'));
            $this->mPost->setDescription($request->get('description'));
            $this->mPost->setDisplayedHome(UtileService::TINY_INT_TRUE);
            $this->mPost->setIsFromOtherWeb($request->get('is_from_other_web'));
            $this->mPost->setOtherWeb($request->get('other_web'));
            $this->mPost->setDisplayedHome(UtileService::TINY_INT_TRUE);
            $this->mPost->setSlug($this->prepareSlug($this->mPost->getTitle()));

            $errorsPost = $this->validator->validate($this->mPost);
            if (count($errorsPost) > 0) {
                $message = $this->translator->trans(
                        $errorsPost[0]->getMessage()
                );
                $this->utileService->setResponsePath(array('field' => $errorsPost[0]->getPropertyPath()));
                $this->utileService->setResponseMessage($message);
                $this->utileService->setResponseState(false);
                return $this->utileService->getResponse();
            }

            $this->em->persist($this->mPost);
            $this->em->flush();
            $this->utileService->setResponseData($this->mPost);
            $this->utileService->setResponseFrom(UtileService::FROM_SQL);
            return $this->utileService->getResponse();
            
            } catch (\Exception $e) {
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($e->getMessage());
                return $this->utileService->getResponse();
            }
    }
	
    public function deletePost($internal_id_post, $internal_id, $internal_token)
    {
        try{
            $user = $this->findUserByInternalToken($internal_token);
            if(!$user){
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.internal_token.wrong'));
                return $this->utileService->getResponse();
            }

            if($user->getInternalId() !== $internal_id){
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.internal_id.not.exist'));
                return $this->utileService->getResponse();
            }
            
            $this->mPost = $this->findPostByInternalId($internal_id_post);
            if(!$this->mPost){
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('post.not.exist'));
                return $this->utileService->getResponse();
            }
            
            if($this->mPost->getUser()->getId() !== $user->getId()){
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('post.not.yours'));
                return $this->utileService->getResponse();
            }
            
            $this->mPost->setIsDeleted(UtileService::TINY_INT_TRUE);
            $this->em->persist($this->mPost);
            $this->em->flush();
            
            $this->utileService->setResponseState(true);
            $this->utileService->setResponseMessage($this->translator->trans('post.deleted'));
            $this->utileService->setResponseFrom(UtileService::FROM_SQL);
            return $this->utileService->getResponse();
            
        } catch (\Exception $e) {
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->getResponse();
        }
    }
    
    public function enablePost($internal_id_post, $internal_id, $internal_token)
    {
        try{
            $user = $this->findUserByInternalToken($internal_token);
            if(!$user){
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.internal_token.wrong'));
                return $this->utileService->getResponse();
            }

            if($user->getInternalId() !== $internal_id){
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('user.internal_id.not.exist'));
                return $this->utileService->getResponse();
            }
            
            $this->mPost = $this->findPostByInternalId($internal_id_post);
            if(!$this->mPost){
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('post.not.exist'));
                return $this->utileService->getResponse();
            }
            
            if($this->mPost->getUser()->getId() !== $user->getId()){
                $this->utileService->setResponseState(false);
                $this->utileService->setResponseMessage($this->translator->trans('post.not.yours'));
                return $this->utileService->getResponse();
            }
            
            $this->mPost->setIsDeleted(UtileService::CONST_NULL);
            $this->em->persist($this->mPost);
            $this->em->flush();
            
            $this->utileService->setResponseState(true);
            $this->utileService->setResponseMessage($this->translator->trans('post.enabled'));
            $this->utileService->setResponseFrom(UtileService::FROM_SQL);
            return $this->utileService->getResponse();
            
        } catch (\Exception $e) {
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($e->getMessage());
            return $this->utileService->getResponse();
        }
    }
}
	
	
	
