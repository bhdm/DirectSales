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
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:User');

        if ($projectId){
            $project = $this->getDoctrine()->getRepository('AppBundle:Project')->findOneById($projectId);
//            $items = $this->getDoctrine()->getRepository('AppBundle:User')->findBy(array( 'parent' => $this->getUser()));
            $items = $this->getDoctrine()->getRepository('AppBundle:User')->getUsers($projectId,$this->getUser()->getId());
        }else{
            $project = null;
//            if ($this->get('security.context')->isGranted('ROLE_ADMIN')){
//                $items = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();
//            }else{
//                $items = $this->getUser()->getChilds();
//                if ($items == null){
//                    $items = array();
//                }
//            }
            $items = $repo->childrenHierarchy(
                null,
                false, /* true: load all children, false: only direct */
                array(
                    'html' => false,
                )
            );
        }

//        $paginator  = $this->get('knp_paginator');
//        $pagination = $paginator->paginate(
//            $items,
//            $this->get('request')->query->get('page', 1),
//            20
//        );

        return array('items' => $items, 'project' => $project);
    }

    /**
     * @Security("has_role('ROLE_AGENT')")
     * @Route("/add/{projectId}", name="user_add", defaults={"projectId" = null })
     * @Template()
     */
    public function addAction(Request $request, $projectId = null){
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->findOneById($projectId);
        $em = $this->getDoctrine()->getManager();
        $item = new User();
        $form = $this->createForm($this->get('form.type.user'), $item);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                if ($project){
                    $item->getProjects()->add($project);
                }
                $item->setParent($this->getUser());
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
     * @Security("has_role('ROLE_AGENT')")
     * @Route("/edit/{id}", name="user_edit")
     * @Template()
     */
    public function editAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $item = $this->getDoctrine()->getRepository('AppBundle:'.self::ENTITY_NAME)->findOneById($id);
        $form = $this->createForm($this->get('form.type.user'), $item);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
                $password = $encoder->encodePassword($item->getPassword(), $item->getSalt());
                $item->setPassword($password);
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

    /**
     * @Security("has_role('ROLE_AGENT')")
     * @Route("/project/{userId}", name="user_add_project")
     * @Template()
     */
    public function userProjectAction(Request $request, $userId){
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneById($userId);
        $projects = $user->getProjects();

        if ($request->getMethod() == 'POST'){
            $project = $this->getDoctrine()->getRepository('AppBundle:Project')->findOneById($request->request->get('projectId'));
            $operator = $this->getDoctrine()->getRepository('AppBundle:User')->findOneById($userId);
            $em = $this->getDoctrine()->getManager();
            $operator->getProjects()->add($project);
            $em->flush();
        }


        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $projects,
            $this->get('request')->query->get('page', 1),
            20
        );
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')){
            $projects = $this->getDoctrine()->getRepository('AppBundle:Project')->findAll();
        }else{
            $projects = $this->getUser()->getProjects();
        }
        return array('pagination' => $pagination, 'user' => $user, 'projects' => $projects);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/project/remove/{userId}/{projectId}", name="user_project_remove")
     */
    public function userProjectRemoveAction(Request $request, $userId, $projectId){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneById($userId);
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->findOneById($projectId);

        $project->getUsers()->removeElement($user);
        $user->getProjects()->removeElement($project);
        $em->flush();
        return $this->redirect($request->headers->get('referer'));
    }


}