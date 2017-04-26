<?php
namespace ApiBundle\Services;

use ApiBundle\Services\UtileService;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;


class PhotoService {
    protected $mailer;

    protected $template;

    protected $usersService;

    protected $utileService;

    protected $allowedExtensionsPhoto = array("jpeg","jpg","bmp","gif","png","png8","png24");

    protected $sizeLimitPhoto;

    protected $uploadDirectory;

    protected $profilePhotoDirectory;

    protected $userPhotoDirectory;

    protected $postPhotoDirectory;

    protected $originalDirectory;

    protected $bigDirectory;

    protected $smallDirectory;

    const PROFILE_PHOTO_TYPE = 1;

    const USER_PHOTO_TYPE = 2;

    const POST_PHOTO_TYPE = 3;

    const ORIGINAL_LEVEL = 1;

    const BIG_LEVEL = 2;

    const SMALL_LEVEL = 3;

    const MIN_LENGTH_FILE = 8;

    public function __construct($mailer, $template, UsersService $usersService, UtileService $utileService, $size_limit_photo, $upload_directory,
                                $profile_photo_directory, $user_photo_directory, $post_photo_directory, $original_directory, $big_directory, $small_directory)
    {
        $this->mailer = $mailer;
        $this->template = $template;
        $this->usersService = $usersService;
        $this->utileService = $utileService;
        $this->sizeLimitPhoto = $size_limit_photo;
        $this->uploadDirectory = $upload_directory;
        $this->profilePhotoDirectory = $profile_photo_directory;
        $this->userPhotoDirectory = $user_photo_directory;
        $this->postPhotoDirectory = $post_photo_directory;
        $this->originalDirectory = $original_directory;
        $this->bigDirectory = $big_directory;
        $this->smallDirectory = $small_directory;
    }

    public function uploadEntry(Request $request)
    {
        return $this->uploadFile($request);
    }


    protected function uploadFile(Request $request)
    {
        $qqfile_name = $request->query->get('qqfile');
        //$filename = $this->file->getName();
        $uploader = new qqFileUploader($this->allowedExtensionsPhoto, $this->sizeLimitPhoto);
        $file_name = UtileService::RandomString(self::MIN_LENGTH_FILE) . UtileService::getDateTimeMicroseconds();
        $result_upload = $uploader->handleUpload($this->getDirectory($request->get('type'), self::ORIGINAL_LEVEL), $file_name);
        if(array_key_exists('success',$result_upload)){
            $photo_path = $this->getDirectory($request->get('type'), self::ORIGINAL_LEVEL).$result_upload['newFilename'];
        } else {
            $this->utileService->setResponseState(false);
            if(array_key_exists('error',$result_upload)) {
                $this->utileService->setResponseMessage($result_upload['error']);
            }
            return $this->utileService->response;
        }
        $this->utileService->setResponseData($photo_path);
        $this->utileService->setResponseState(true);
        return $this->utileService->response;
    }
    /*
     * $type : 1 ==> profile, 2 ==> user; 3 ==> post
     * $level: 1 ==> original, 2 ==> big, 3 ==> small
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
            case self::BIG_LEVEL:
                $return .= $this->bigDirectory;
                break;
            case self::SMALL_LEVEL:
                $return .= $this->smallDirectory;
                break;
            default:
                $this->utileService->setResponseMessage('upload.directory.level.invalid');
                $this->utileService->setResponseState(false);
                return $this->utileService->response;
        }

        if($user_id){
            $return .= $user_id.'/';
        }

        return $return;
    }


}
