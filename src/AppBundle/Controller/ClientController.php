<?php
namespace AppBundle\Controller;

use AppBundle\Entity\StatusLog;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Client;
use AppBundle\Form\ClientType;

/**
 * Class ClientController
 * @package AppBundle\Controller
 * @Route("/client")
 */
class ClientController extends Controller{
    const ENTITY_NAME = 'Client';
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/{projectId}", name="client_list")
     * @Template()
     */
    public function listAction($projectId){
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->findOneById($projectId);
        $items = $this->getDoctrine()->getRepository('AppBundle:'.self::ENTITY_NAME)->findByProject($project);
        $status = $this->getDoctrine()->getRepository('AppBundle:ClientStatus')->findAll();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $items,
            $this->get('request')->query->get('client', 1),
            20
        );

        return array('pagination' => $pagination, 'project' => $project, 'status' => $status);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/add/{projectId}", name="client_add")
     * @Template()
     */
    public function addAction(Request $request, $projectId){
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->findOneById($projectId);
        $em = $this->getDoctrine()->getManager();
        $item = new Client();
        $form = $this->createForm(new ClientType($em), $item);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $item->setProject($project);
                $em->persist($item);
                $em->flush();
                $em->refresh($item);
                return $this->redirect($this->generateUrl('client_list', array('projectId' => $projectId)));
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/edit/{projectId}{id}", name="client_edit")
     * @Template()
     */
    public function editAction(Request $request, $projectId, $id){
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->findOneById($projectId);
        $em = $this->getDoctrine()->getManager();
        $item = $this->getDoctrine()->getRepository('AppBundle:'.self::ENTITY_NAME)->findOneById($id);
        $form = $this->createForm(new ClientType($em), $item);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $em->flush($item);
                $em->refresh($item);
                return $this->redirect($this->generateUrl('client_list',array('projectId' => $project->getId())));
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/remove/{id}", name="client_remove")
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
     * @Route("/status/{clientId}/{statusId}", name="client_status")
     * @Security("has_role('ROLE_AGENT')")
     */
    public function statusAction(Request $request, $clientId, $statusId){
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('AppBundle:'.self::ENTITY_NAME)->findOneById($clientId);
        $status = $em->getRepository('AppBundle:ClientStatus')->findOneById($statusId);
        $item->setStatus($status);

        /** Запись в статус лог */
        $statusLog = new StatusLog();
        $statusLog->setUser($this->getUser());
        $statusLog->setClient($item);
        $statusLog->setStatus($status);
        $date = new \DateTime();
        $statusLog->setCreated($date);
        $em->persist($statusLog);


        $em->flush();
        return $this->redirect($request->headers->get('referer'));
    }
}