<?php

namespace MiddleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use ApiBundle\Controller\PublicPostController as ApiPublicPostController;

class PublicPostController extends ApiPublicPostController
{
    public function getSinglePostPageAction(Request $request)
    {
        return parent::getSinglePostPageAction($request);
    }
    
    public function searchPostAction(Request $request)
    {
        return parent::searchPostAction($request);
    }  
}
