<?php

namespace TestBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="testhomepage")
     */
    public function indexAction()
    {
        return $this->render('TestBundle:Default:index.html.twig');
    }
    
        /**
     * @Route("/email/new/user", name="email")
     */
    public function testEmailNewUserAction(Request $request)
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
    
    
    /**
     * @Route("/email/user/password/changed", name="email_password_changed")
     */
    public function testEmailPasswordChangedAction(Request $request)
    {
        $username = 'qincheng';
        $email = 'bryant.qin@gmai.com';
        $telephone = '0626413030';
        $indication = '1******n';
        $updated = '2017-04-11 14:56:13';
        return $this->render('ApiBundle:Emails:changedPassword.html.twig', array('username' => $username, 
            'telephone' => $telephone,
            'email' => $email,
            'indication' => $indication,
            'updated' => $updated
        ));
    } 
    
    
    /**
     * @Route("/email/user/password/forget", name="email_password_forget")
     */
    public function testEmailPasswordForgetAction(Request $request)
    {
        $username = 'qincheng';
        $email = 'bryant.qin@gmai.com';
        $telephone = '0626413030';
        $indication = '1******n';
        $updated = '2017-04-11 14:56:13';
        return $this->render('ApiBundle:Emails:forgetPassword.html.twig', array('username' => $username, 
            'telephone' => $telephone,
            'email' => $email,
            'indication' => $indication,
            'updated' => $updated
        ));
    } 
}
