<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;


class PublicPostController extends FOSRestController
{
	public function searchPostAction(Request $request)
    {
        $offset = $request->get('offset');
        $limit = $request->get('limit');
        $country_id = $request->get('country_id');
        $location_id = $request->get('location_id');
        $word = $request->get('word');
        $only_total = $request->get('only_total');
        return $this->container->get('api_massage.SearchService')->searchPostManager(
                $only_total, $offset, $limit, $country_id, $location_id, $word);
    }   
	
	public function getSinglePostPageAction(Request $request)
    {
        return $this->container->get('api_massage.PostsService')->getSinglePostPageById($request->get('id'));
    }
}