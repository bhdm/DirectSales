<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;

/**
 * Class UserController
 * @package AppBundle\Controller
 * @Route("/user")
 */
class UserController extends Controller{
    const ENTITY_NAME = 'User';
    /**
     * @Security("has_role('ROLE_AGENT')")
     * @Route("/{projectId}", name="user_list", defaults={"projectId" = null },  requirements={ "projectId": "\d+"}))
     * @Template()
     */
    public function listAction($projectId = null){
        if ($projectId){
            $project = $this->getDoctrine()->getRepository('AppBundle:Project')->findOneById($projectId);
            $items = $project->getUsers();
        }else{
            if ($this->get('security.context')->isGranted('ROLE_ADMIN')){
                $project = null;
                $items = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();
            }
        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $items,
            $this->get('request')->query->get('user', 1),
            20
        );

        return array('pagination' => $pagination, 'project' => $project);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/add/{projectId}", name="user_add", defaults={"projectId" = null })
     * @Template()
     */
    public function addAction(Request $request, $projectId = null){
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->findOneById($projectId);
        $em = $this->getDoctrine()->getManager();
        $item = new User();
        $form = $this->createForm(new UserType($em), $item);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $item->getProjects()->add($project);
                $item->setSalt(md5(time()));
                $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
                $password = $encoder->encodePassword($item->getPassword(), $item->getSalt());
                $item->setPassword($password);
                $em->persist($item);
                $em->flush();
                $em->refresh($item);
                return $this->redirect($this->generateUrl('user_list', array('projectId' => $projectId)));
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/edit/{id}", name="user_edit")
     * @Template()
     */
    public function editAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $item = $this->getDoctrine()->getRepository('AppBundle:'.self::ENTITY_NAME)->findOneById($id);
        $form = $this->createForm(new UserType($em), $item);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $em->flush($item);
                $em->refresh($item);
                return $this->redirect($this->generateUrl('user_list'));
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/remove/{id}", name="user_remove")
     */
    public function removeAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('AppBundle:'.self::ENTITY_NAME)->findOneById($id);
        if ($item){
            $em->remove($item);
            $em->flush();
        }
        return $this->redirect($request->headers->get('referer'));
    }
}