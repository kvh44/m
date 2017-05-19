<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;


class PrivatePostController extends FOSRestController
{
    public function createPostAction(Request $request)
    {
        return $this->container->get('api_massage.PostsService')->createPost($request);
    }
	
	public function deletePostAction(Request $request)
    {
        $internal_id_post = $request->headers->get('internal_id_post');
        $internal_id = $request->headers->get('internal_id');
        $internal_token = $request->headers->get('internal_token');
        return $this->container->get('api_massage.PostsService')->deletePost($internal_id_post, $internal_id, $internal_token);
    } 
	
	public function updatePostAction(Request $request)
    {
        $internal_id_post = $request->headers->get('internal_id_post');
        $internal_id = $request->headers->get('internal_id');
        $internal_token = $request->headers->get('internal_token');
        return $this->container->get('api_massage.PostsService')->updatePost($internal_id_post, $internal_id, $internal_token);
    } 
}