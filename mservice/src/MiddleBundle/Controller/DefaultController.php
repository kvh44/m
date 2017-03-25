<?php

namespace MiddleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MiddleBundle:Default:index.html.twig');
    }
}
