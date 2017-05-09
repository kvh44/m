<?php
namespace ApiBundle\Services;

use ApiBundle\Services\UtileService;
use ApiBundle\Entity\Mphoto;
use ApiBundle\Services\CacheService;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use ApiBundle\Util\SimpleImage\SimpleImageClaviska;
use ApiBundle\Util\Qq\qqFileUploader;

class PhotoService {
    
    /**
     * @var Registry
     */
    protected $doctrine;

    /*
     * @var em
     */
    protected $em;

    protected $usersService;

    protected $utileService;

    protected $allowedExtensionsPhoto = array("jpeg","jpg","bmp","gif","png","png8","png24");

    protected $sizeLimitPhoto;

    protected $uploadDirectory;

    protected $profilePhotoDirectory;

    protected $userPhotoDirectory;

    protected $postPhotoDirectory;

    protected $originalDirectory;

    protected $mediumDirectory;

    protected $smallDirectory;
    
    protected $iconDirectory;

    protected $mediumPhotoMaxWidth;

    protected $smallPhotoMaxWidth;

    protected $iconPhotoMaxWidth;

    protected $photoDefaultMimeType;

    protected $photoDefaultType;

    protected $user;
    
    protected $mPhoto;
    
    protected $cacheService;

    const PROFILE_PHOTO_TYPE = 1;

    const USER_PHOTO_TYPE = 2;

    const POST_PHOTO_TYPE = 3;

    const ORIGINAL_LEVEL = 1;

    const MEDIUM_LEVEL = 2;

    const SMALL_LEVEL = 3;

    const ICON_LEVEL = 4;

    const MIN_LENGTH_FILE = 8;

    public function __construct(Registry $doctrine, UsersService $usersService, CacheService $cacheService, UtileService $utileService, $size_limit_photo, $upload_directory,
                                $profile_photo_directory, $user_photo_directory, $post_photo_directory, $original_directory, $medium_directory, 
                                $small_directory, $medium_photo_max_width, $small_photo_max_width, $icon_photo_max_width, $photo_default_mime_type,
                                $photo_default_type)
    {
        $this->doctrine = $doctrine;
        $this->em = $this->doctrine->getManager();
        $this->usersService = $usersService;
        $this->utileService = $utileService;
        $this->sizeLimitPhoto = (int)$size_limit_photo;
        $this->uploadDirectory = $upload_directory;
        $this->profilePhotoDirectory = $profile_photo_directory;
        $this->userPhotoDirectory = $user_photo_directory;
        $this->postPhotoDirectory = $post_photo_directory;
        $this->originalDirectory = $original_directory;
        $this->mediumDirectory = $medium_directory;
        $this->smallDirectory = $small_directory;
        $this->mediumPhotoMaxWidth = (int)$medium_photo_max_width;
        $this->smallPhotoMaxWidth = (int)$small_photo_max_width;
        $this->iconPhotoMaxWidth = (int)$icon_photo_max_width;
        $this->photoDefaultMimeType = $photo_default_mime_type;
        $this->photoDefaultType = $photo_default_type;
        $this->cacheService = $cacheService;
    }

    public function uploadEntry(Request $request, $is_local)
    {
        $this->user = $this->findUserByInternalToken($request->get('internal_token'));
        if(!$this->user){
            $this->utileService->setResponseMessage('user.token.wrong');
            $this->utileService->setResponseState(false);
            return $this->utileService->response;
        }
        return $this->uploadFile($request, $is_local);
    }

    public function findUserByInternalToken($internal_token)
    {
        return $this->usersService->findUserByInternalToken($internal_token);
    }


    protected function uploadFile(Request $request, $is_local = false)
    {
        try{
            $qqfile_name = $request->query->get('qqfile');

            $uploader = new qqFileUploader($this->allowedExtensionsPhoto, $this->sizeLimitPhoto);
            $file_name = UtileService::RandomString(self::MIN_LENGTH_FILE) . UtileService::getDateTimeMicroseconds();
            $directory_original = $this->getDirectory($request->get('type'), self::ORIGINAL_LEVEL, $this->user->getId());
            if(is_array($directory_original)){
                return $directory_original;
            }
            $result_upload = $uploader->handleUpload($directory_original, $file_name, $is_local);
            $file_name_origin = $result_upload['newFilename'];
            if(array_key_exists('success',$result_upload)){
                $original_photo_path = $directory_original . $file_name_origin;
            } else {
                $this->utileService->setResponseState(false);
                if(array_key_exists('error',$result_upload)) {
                    $this->utileService->setResponseMessage($result_upload['error']);
                }
                return $this->utileService->response;
            }



            // generate medium
            $directory_medium = $this->getDirectory($request->get('type'), self::MEDIUM_LEVEL, $this->user->getId());
            if(is_array($directory_medium)){
                return $directory_medium;
            }
            $imageinformation=getimagesize($original_photo_path);
            if($imageinformation[0] > $this->mediumPhotoMaxWidth){
                $width = $this->mediumPhotoMaxWidth;
                $height = $this->mediumPhotoMaxWidth * ( $imageinformation[1] / $imageinformation[0]);
            } else {
                $width = $imageinformation[0];
                $height = $imageinformation[0] * ( $imageinformation[1] / $imageinformation[0]);
            }
            $file_name_medium = $file_name . '-' . $width . 'x' . $height . $this->photoDefaultType;
            $medium_photo_path = $this->generateSmallPhoto($original_photo_path, $directory_medium, $file_name_medium, $this->photoDefaultMimeType, $width, $height);
            if(is_array($medium_photo_path)){
                return $medium_photo_path;
            }



            // generate small
            $directory_small = $this->getDirectory($request->get('type'), self::SMALL_LEVEL, $this->user->getId());
            if(is_array($directory_small)){
                return $directory_small;
            }
            $imageinformation=getimagesize($original_photo_path);
            if($imageinformation[0] > $this->smallPhotoMaxWidth){
                $width = $this->smallPhotoMaxWidth;
                $height = $this->smallPhotoMaxWidth * ( $imageinformation[1] / $imageinformation[0]);
            } else {
                $width = $imageinformation[0];
                $height = $imageinformation[0] * ( $imageinformation[1] / $imageinformation[0]);
            }
            $file_name_small = $file_name . '-' . $width . 'x' . $height . $this->photoDefaultType;
            $small_photo_path = $this->generateSmallPhoto($original_photo_path, $directory_small, $file_name_small, $this->photoDefaultMimeType, $width, $height);
            if(is_array($small_photo_path)){
                return $small_photo_path;
            }



            // generate icon
            $directory_icon = $this->getDirectory($request->get('type'), self::ICON_LEVEL, $this->user->getId());
            if(is_array($directory_icon)){
                return $directory_icon;
            }
            $imageinformation=getimagesize($original_photo_path);
            if($imageinformation[0] > $this->iconPhotoMaxWidth){
                $width = $this->iconPhotoMaxWidth;
                $height = $this->iconPhotoMaxWidth * ( $imageinformation[1] / $imageinformation[0]);
            } else {
                $width = $imageinformation[0];
                $height = $imageinformation[0] * ( $imageinformation[1] / $imageinformation[0]);
            }
            $file_name_icon = $file_name . '-' . $width . 'x' . $height . $this->photoDefaultType;
            $icon_photo_path = $this->generateSmallPhoto($original_photo_path, $directory_icon, $file_name_icon, $this->photoDefaultMimeType, $width, $height);
            if(is_array($icon_photo_path)){
                return $icon_photo_path;
            }
        } catch(\Exception $err) {
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($err->getMessage());
            return $this->utileService->response;
        }
        
        try{
            $this->mPhoto = new Mphoto();
            $this->mPhoto->setUser($this->user);
            $this->mPhoto->setPhotoType($request->get('type'));
            if($request->get('type') === self::POST_PHOTO_TYPE){
                if(!$request->get('post_id')){
                    $this->utileService->setResponseMessage('post.id.invalid');
                    $this->utileService->setResponseState(false);
                    return $this->utileService->response;
                }
                $this->mPhoto->setPostId($request->get('post_id'));
            }
            $this->mPhoto->setPhotoOrigin($file_name_origin);
            $this->mPhoto->setPhotoMedium($file_name_medium);
            $this->mPhoto->setPhotoSmall($file_name_small);
            $this->mPhoto->setPhotoIcon($file_name_icon);
            $this->mPhoto->setInternalId(UtileService::RandomString(self::MIN_LENGTH_FILE) . UtileService::getDateTimeMicroseconds());
            $this->em->persist($this->mPhoto);
            $this->em->flush();
            
            // update user updated time
            $user = $this->usersService->findUserByInternalId($this->mPhoto->getUser()->getInternalId());
            $user->setUpdated($user->getUpdated()->format('Y-m-d H:i:s'));
            $this->em->persist($user);
            $this->em->flush();
            
            $this->utileService->setResponseData(
                    array(
                        'user_id' => $this->mPhoto->getUser()->getId(), 
                        'user_internal_id' => $this->mPhoto->getUser()->getInternalId(),
                        'photo_id' => $this->mPhoto->getId(), 
                        'photo_internal_id' => $this->mPhoto->getInternalId(),
                        'original_photo_path' => $original_photo_path,
                        'icon_photo_path' => $icon_photo_path, 
                        'small_photo_path' => $small_photo_path, 
                        'medium_photo_path' => $medium_photo_path
                    )
            );
            $this->utileService->setResponseState(true);
            return $this->utileService->response;
        } catch(\Exception $err) {
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($err->getMessage());
            return $this->utileService->response;
        }
    }

    protected function generateSmallPhoto($photo_path, $directory, $new_name, $mime_type = 'image/png', $width = 100, $height = 100)
    {
       if(!file_exists($photo_path)){
           $this->utileService->setResponseMessage('photo.original.not_exist');
           $this->utileService->setResponseState(false);
           return $this->utileService->response;
       }

        try {
            // Create a new SimpleImage object
            $image = new SimpleImageClaviska();

            $image
                ->fromFile($photo_path)                     // load image.jpg
                ->autoOrient()                              // adjust orientation based on exif data
                ->resize($width, $height)                          // resize to 320x200 pixels
                ->flip('x')                                 // flip horizontally
                //->colorize('DarkBlue')                      // tint dark blue
                //->border('black', 10)                       // add a 10 pixel black border
                //->overlay('watermark.png', 'bottom right')  // add a watermark image
                ->toFile($directory . $new_name, $mime_type)
                ;
                // convert to PNG and save a copy to new-image.png
                //->toScreen();                               // output to the screen

            return $directory . $new_name;
        } catch(\Exception $err) {
            // Handle errors
            $this->utileService->setResponseState(false);
            $this->utileService->setResponseMessage($err->getMessage());
            return $this->utileService->response;
        }
    }

    /*
     * $type : 1 ==> profile, 2 ==> user; 3 ==> post
     * $level: 1 ==> original, 2 ==> medium, 3 ==> small
     */
    protected function getDirectory($type, $level, $user_id = null)
    {
        $return = '';
        switch ($type){
            case self::PROFILE_PHOTO_TYPE:
                $return = $this->uploadDirectory . $this->profilePhotoDirectory;
                break;
            case self::USER_PHOTO_TYPE:
                $return = $this->uploadDirectory . $this->userPhotoDirectory;
                break;
            case self::POST_PHOTO_TYPE:
                $return = $this->uploadDirectory . $this->postPhotoDirectory;
                break;
            default:
                $this->utileService->setResponseMessage('upload.directory.type.invalid');
                $this->utileService->setResponseState(false);
                return $this->utileService->response;
        }

        switch ($level){
            case self::ORIGINAL_LEVEL:
                $return .= $this->originalDirectory;
                break;
            case self::MEDIUM_LEVEL:
                $return .= $this->mediumDirectory;
                break;
            case self::SMALL_LEVEL:
                $return .= $this->smallDirectory;
                break;
            case self::ICON_LEVEL:
                $return .= $this->iconDirectory;
                break;
            default:
                $this->utileService->setResponseMessage('upload.directory.level.invalid');
                $this->utileService->setResponseState(false);
                return $this->utileService->response;
        }

        if($user_id){
            $return .= $user_id.'/';
        }

        if(!is_dir($return)){
            mkdir($return, 0777, true);
        }

        if(!is_writable($return)){
            chmod($return, 777);
        }

        return $return;
    }
    
    
    public function findUserPhotosByUserId($user_id)
    {
        return $this->em->getRepository('ApiBundle:Mphoto')->loadUserPhotosByUserId($user_id);
    }        
    
    public function findProfilePhotosByUserId($user_id)
    {
        return $this->em->getRepository('ApiBundle:Mphoto')->loadProfilePhotosByUserId($user_id);
    }        

    public function findUserPhotosByUserIdCache($user_id)
    {
        $userPhotos = $this->cacheService->getSingleUserPhotosByUserIdCache($user_id);
        $this->utileService->setResponseFrom(UtileService::FROM_CACHE);
        
        
        if(!$userPhotos){
            $userPhotos = $this->findUserPhotosByUserId($user_id);
            $this->utileService->setResponseFrom(UtileService::FROM_SQL);
            $this->cacheService->setSingleUserPhotosByUserIdCache($user_id, serialize($userPhotos));
        } else {
            $userPhotos = unserialize($userPhotos);
        }
        
        $this->utileService->setResponseState(true);
        $data = array(UtileService::DATA_STRUCTURE_USER_PHOTOS => $userPhotos);
        $this->utileService->setResponseData($data);
        return $this->utileService->getResponse();
    } 
    
    public function findProfilePhotosByUserIdCache($user_id)
    {
        $profilePhoto = $this->cacheService->getProfilePhotoByUserIdCache($user_id);
        $this->utileService->setResponseFrom(UtileService::FROM_CACHE);
        
        
        if(!$profilePhoto){
            $profilePhoto = $this->findProfilePhotosByUserId($user_id);
            $this->utileService->setResponseFrom(UtileService::FROM_SQL);
            $this->cacheService->setProfilePhotoByUserIdCache($user_id, serialize($profilePhoto));
        } else {
            $profilePhoto = unserialize($profilePhoto);
        }
        
        $this->utileService->setResponseState(true);
        $data = array(UtileService::DATA_STRUCTURE_PROFILE_PHOTO => $profilePhoto);
        $this->utileService->setResponseData($data);
        return $this->utileService->getResponse();
    } 
}
