<?php
namespace AppBundle\Controller;

use AppBundle\Entity\EventAnswer;
use AppBundle\Entity\EventQuestion;
use AppBundle\Form\EventQuestionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class EventController
 * @package AppBundle\Controller
 * @Route("/event/question")
 */
class EventQuestionController extends Controller{
    const ENTITY_NAME = 'EventQuestion';
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/{eventId}", name="event_question_list")
     * @Template()
     */
    public function listAction($eventId){
        $event = $this->getDoctrine()->getRepository('AppBundle:Event')->findOneById($eventId);
        $items = $this->getDoctrine()->getRepository('AppBundle:'.self::ENTITY_NAME)->findByEvent($event);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $items,
            $this->get('request')->query->get('event', 1),
            20
        );

        return array('pagination' => $pagination, 'event' => $event);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/add/{eventId}", name="event_question_add")
     * @Template()
     */
    public function addAction(Request $request, $eventId){
        $event = $this->getDoctrine()->getRepository('AppBundle:Event')->findOneById($eventId);
        $em = $this->getDoctrine()->getManager();
        $item = new EventQuestion();
        $form = $this->createForm(new EventQuestionType($em), $item);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $item->setEvent($event);
                $em->persist($item);
                $em->flush();
                $em->refresh($item);
                return $this->redirect($this->generateUrl('event_question_list', array('eventId' => $eventId)));
            }
        }
        return array('form' => $form->createView(), 'event' => $event);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/edit/{id}", name="event_question_edit")
     * @Template()
     */
    public function editAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $item = $this->getDoctrine()->getRepository('AppBundle:'.self::ENTITY_NAME)->findOneById($id);
        $form = $this->createForm(new EventQuestionType($em), $item);
        $formData = $form->handleRequest($request);

        if ($request->getMethod() == 'POST'){
            if ($formData->isValid()){
                $item = $formData->getData();
                $em->flush($item);
                $em->refresh($item);
                return $this->redirect($this->generateUrl('event_question_list'));
            }
        }
        return array('form' => $form->createView(), 'event' => $item);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/remove/{id}", name="event_question_remove")
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
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/{eventId}/answer", name="event_answer_list")
     * @Template()
     */
    public function answerAction(Request $request, $eventId){
        $event = $this->getDoctrine()->getRepository('AppBundle:Event')->findOneById($eventId);
        $questions = $event->getQuestions();
        $project = $event->getProject();
        $clients = $project->getClients();

        if ($request->getMethod() == 'POST'){
            $em = $this->getDoctrine()->getManager();
            $client = $this->getDoctrine()->getRepository('AppBundle:Client')->findOneById($request->request->get('client'));
            $date = new \DateTime();
            foreach ( $request->request->get('answer') as $k => $a ){
                $question = $this->getDoctrine()->getRepository('AppBundle:EventQuestion')->findOneById($k);
                $answer = new EventAnswer();
                $answer->setUser($this->getUser());
                $answer->setClient($client);
                $answer->setQuestion($question);
                $answer->setTitle($a);
                $answer->setCreated($date);
                $answer->setUpdated($date);
                $em->persist($answer);
            }
            $em->flush($answer);
            return $this->redirect($this->generateUrl('event_list', array('projectId' => $event->getProject()->getId())));
        }

        return array(
            'event' => $event,
            'questions' => $questions,
            'clients' => $clients,
        );
    }
}