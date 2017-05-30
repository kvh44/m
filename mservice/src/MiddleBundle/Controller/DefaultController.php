<?php

namespace MiddleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

class DefaultController extends FOSRestController
{
    public function indexAction()
    {
        return $this->render('MiddleBundle:Default:index.html.twig');
    }
}
