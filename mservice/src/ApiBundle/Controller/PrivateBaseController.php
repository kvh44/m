<?php

namespace ApiBundle\Controller;

use ApiBundle\Controller\PublicBaseController;

class PrivateBaseController extends PublicBaseController
{
    public function preExecuteMiddle()
    {
        $this->get('request_stack')->getCurrentRequest()->headers->set('api_application_key' , $this->container->getParameter('api_application_key'));
    }        

    public function preExecute()
    {
        // application key
        if($this->get('request_stack')->getCurrentRequest()->headers->get('api_application_key') !== $this->container->getParameter('api_application_key')){
            die($this->get('translator')->trans('application.not.accepted'));
        }
        
        // from external token to internal token
        if($this->get('request_stack')->getCurrentRequest()->headers->get('external_token') !== null && $this->get('request_stack')->getCurrentRequest()->headers->get('internal_id') !== null){
            $external_token = $this->get('request_stack')->getCurrentRequest()->headers->get('external_token');
            $internal_id = $this->get('request_stack')->getCurrentRequest()->headers->get('internal_id');
            $internal_token = $this->container->get('api_massage.UsersService')->findInternalTokenByExternalToken($internal_id, $external_token);
            if(!$internal_token || count($internal_token) !== 1 || !array_key_exists('internalToken', $internal_token)){
                die($this->get('translator')->trans('user.external_token.wrong'));
            }else {
                $this->get('request_stack')->getCurrentRequest()->headers->set('internal_token' , $internal_token['internalToken']);
            }
        }
    }
}