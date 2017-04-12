<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }
    
    /**
     * @Route("/email", name="email")
     */
    public function testEmailAction(Request $request)
    {
        $username = 'qincheng';
        $email = 'bryant.qin@gmai.com';
        $telephone = '0626413030';
        $indication = '1******n';
        $created = '2017-04-11 14:56:13';
        return $this->render('ApiBundle:Emails:registration.html.twig', array('username' => $username, 
            'telephone' => $telephone,
            'email' => $email,
            'indication' => $indication,
            'created' => $created
        ));
    } 
}
