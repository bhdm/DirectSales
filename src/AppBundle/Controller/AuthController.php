<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class AuthController extends Controller
{

    /**
     * @Security("has_role('ROLE_OPERATOR')")
     * @Route("/", name="main")
     */
    public function indexAction(){
//        if ($this->get('security.context')->isGranted('ROLE_ADMIN')){
//            return $this->redirect($this->generateUrl('panel_user_list'));
//        }elseif($this->get('security.context')->isGranted('ROLE_MODERATOR')){
//            return $this->redirect($this->generateUrl('panel_company_stats'));
//        }else{
//            return $this->redirect($this->generateUrl('panel_user_list'));
//        }
    }

    /**
     * @Route("/login", name="login")
     * @Template()
     */
    public function loginAction()
    {
        if ($this->get('request')->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $this->get('request')->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $this->get('request')->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }
        return array(
            'error' => $error,
        );
    }

    /**
     * @Route("/register", name="register")
     * @Template()
     */
    public function registerAction(){
        return array();
    }
}
