<?php

namespace FrontBundle\Twig;
use Symfony\Component\DependencyInjection\Container;
use ApiBundle\Services\PhotoService;

class AppExtension extends \Twig_Extension
{
	
	/**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }
	
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('photoTypeDirectory', array($this, 'photoTypeDirectoryFilter')),
        );
    }

    public function photoTypeDirectoryFilter($phototype)
    {
		$twigGlobals = $this->container->get('twig')->getGlobals();
		
        switch ($phototype) {
			case PhotoService::PROFILE_PHOTO_TYPE :
			    return $twigGlobals['profile_photo_directory'];
				break;
			case PhotoService::USER_PHOTO_TYPE :
                return $twigGlobals['user_photo_directory'];
				break;			
			case PhotoService::POST_PHOTO_TYPE :
                return $twigGlobals['post_photo_directory'];
				break;
            default:	
                return '';				
			   
		}
    }
}