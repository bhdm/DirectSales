<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Address;
use AppBundle\Form\AddressType;

/**
 * Class AddressController
 * @package AppBundle\Controller
 * @Route("/adrs")
 */
class AddressController extends Controller{
    const ENTITY_NAME = 'Address';
    /**
     * @Security("has_role('ROLE_AGENT')")
     * @Route("/{projectId}", name="adrs_list")
     * @Template()
     */
    public function listAction($projectId){
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->findOneById($projectId);
        $items = $this->getDoctrine()->getRepository('AppBundle:'.self::ENTITY_NAME)->findByProject($project);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $items,
            $this->get('request')->query->get('adrs', 1),
            20
        );

        return array('pagination' => $pagination, 'project' => $project);
    }

    /**
     * @Security("has_role('ROLE_OPERATOR')")
     * @Route("/add/{projectId}", name="adrs_add")
     * @Template()
     */
    public function addAction(Request $request, $projectId){
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->findOneById($projectId);
        $em = $this->getDoctrine()->getManager();
        $item = new Address();
        $form = $this->createForm(new AddressType($em), $item);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $item->setProject($project);
                $em->persist($item);
                $em->flush();
                $em->refresh($item);
                return $this->redirect($this->generateUrl('adrs_list', array('projectId' => $projectId)));
            }
        }
        return array('form' => $form->createView(), 'project' => $project);
    }

    /**
     * @Security("has_role('ROLE_OPERATOR')")
     * @Route("/edit/{id}", name="adrs_edit")
     * @Template()
     */
    public function editAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $item = $this->getDoctrine()->getRepository('AppBundle:'.self::ENTITY_NAME)->findOneById($id);
        $form = $this->createForm(new AddressType($em), $item);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $em->flush($item);
                $em->refresh($item);
                return $this->redirect($this->generateUrl('adrs_list', array('projectId' => $item->getProject()->getId())));
            }
        }
        return array('form' => $form->createView(), 'project' => $item->getProject());
    }

    /**
     * @Security("has_role('ROLE_OPERATOR')")
     * @Route("/remove/{id}", name="adrs_remove")
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
     * @Security("has_role('ROLE_OPERATOR')")
     * @Route("/poll-stats/{adrsId}", name="adrs_poll_stats")
     * @Template()
     */
    public function pollStatsAction($adrsId){
        /**
         * Вывод списка вопросов, варианты ответов, и кол-во одинаковых ответов
         */
        $adrs = $this->getDoctrine()->getRepository('AppBundle:Address')->findOneById($adrsId);
        $stats = $this->getDoctrine()->getRepository('AppBundle:Address')->pollStats($adrsId);

        return array(
            'adrs' => $adrs,
            'stats' => $stats,
        );
    }
}