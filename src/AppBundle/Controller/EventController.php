<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Event;
use AppBundle\Form\EventType;

/**
 * Class EventController
 * @package AppBundle\Controller
 * @Route("/event")
 */
class EventController extends Controller{
    const ENTITY_NAME = 'Event';
    /**
     * @Security("has_role('ROLE_AGENT')")
     * @Route("/{projectId}", name="event_list")
     * @Template()
     */
    public function listAction($projectId){
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->findOneById($projectId);
        $items = $this->getDoctrine()->getRepository('AppBundle:'.self::ENTITY_NAME)->findByProject($project);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $items,
            $this->get('request')->query->get('event', 1),
            20
        );

        return array('pagination' => $pagination, 'project' => $project);
    }

    /**
     * @Security("has_role('ROLE_OPERATOR')")
     * @Route("/add/{projectId}", name="event_add")
     * @Template()
     */
    public function addAction(Request $request, $projectId){
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->findOneById($projectId);
        $em = $this->getDoctrine()->getManager();
        $item = new Event();
        $form = $this->createForm(new EventType($em), $item);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $item->setProject($project);
                $em->persist($item);
                $em->flush();
                $em->refresh($item);
                return $this->redirect($this->generateUrl('event_list', array('projectId' => $projectId)));
            }
        }
        return array('form' => $form->createView());
    }

    /**
     * @Security("has_role('ROLE_OPERATOR')")
     * @Route("/edit/{id}", name="event_edit")
     * @Template()
     */
    public function editAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $item = $this->getDoctrine()->getRepository('AppBundle:'.self::ENTITY_NAME)->findOneById($id);
        $form = $this->createForm(new EventType($em), $item);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $em->flush($item);
                $em->refresh($item);
                return $this->redirect($this->generateUrl('event_list', array('projectId' => $item->getProject()->getId())));
            }
        }
        return array('form' => $form->createView(), 'event' => $item);
    }

    /**
     * @Security("has_role('ROLE_OPERATOR')")
     * @Route("/remove/{id}", name="event_remove")
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
     * @Route("/poll-stats/{eventId}", name="event_poll_stats")
     * @Template()
     */
    public function pollStatsAction($eventId){
        /**
         * Вывод списка вопросов, варианты ответов, и кол-во одинаковых ответов
         */
        $event = $this->getDoctrine()->getRepository('AppBundle:Event')->findOneById($eventId);
        $stats = $this->getDoctrine()->getRepository('AppBundle:Event')->pollStats($eventId);

        return array(
            'event' => $event,
            'stats' => $stats,
        );
    }
}