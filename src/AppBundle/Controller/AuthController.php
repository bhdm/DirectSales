<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class AuthController extends Controller
{

    /**
     * @Route("/", name="main")
     */
    public function indexAction(){
        if ($this->getUser()){
            return $this->redirect($this->generateUrl('project_list'));
        }else{
            return $this->redirect($this->generateUrl('login'));
        }

//        if ($this->get('security.context')->isGranted('ROLE_ADMIN')){
//            return $this->redirect($this->generateUrl('panel_user_list'));
//        }elseif($this->get('security.context')->isGranted('ROLE_MODERATOR')){
//            return $this->redirect($this->generateUrl('panel_company_stats'));
//        }else{
//            return $this->redirect($this->generateUrl('panel_user_list'));
//        }

//        $manager = $this->getDoctrine()->getManager();
//        $user = new User();
//        $user->setFirstName('admin');
//        $user->setLastName('admin');
//        $user->setSurName('admin');
//        $user->setUsername('admin');
//        $user->setCreated(new \DateTime());
//        $user->setUpdated(new \DateTime());
//        $user->setSalt(md5(time()));
//        $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
//        $password = $encoder->encodePassword('admin', $user->getSalt());
//        $user->setPassword($password);
//        $user->setRoles('ROLE_ADMIN');
//
//        $manager->persist($user);
//        $manager->flush();
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
