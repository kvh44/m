<?php

namespace MiddleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use ApiBundle\Controller\PrivatePostController as ApiPrivatePostController;

class PrivatePostController extends ApiPrivatePostController
{
    public function createPostAction(Request $request)
    {
        return parent::createPostAction($request);
    }
    
    public function updatePostAction(Request $request)
    {
        return parent::updatePostAction($request);
    } 
    
    public function deletePostAction(Request $request)
    {
        return parent::deletePostAction($request);
    }
}
