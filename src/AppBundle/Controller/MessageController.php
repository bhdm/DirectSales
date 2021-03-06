<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @Security("has_role('ROLE_AGENT')")
 * @Route("/panel/message")
 */
class MessageController extends Controller
{
    /**
     * @Route("/list/{operatorId}", name="panel_message", defaults={"operatorId" = null})
     * @Template()
     */
    public function indexAction(Request $request, $operatorId){
        $em = $this->getDoctrine()->getManager();
        if ( $request->getMethod() == 'POST' ){
            $data = $request->request;
            $message = new Message();
            $subUser = $this->getDoctrine()->getRepository('AppBundle:User')->findOneById($data->get('operatorId'));
            $message->setSender($this->getUser());
            $message->setReceiver($subUser);

            $message->setBody($data->get('body'));
            $em->persist($message);
            $em->flush();
        }

        if ( $operatorId == null){
            $operatorId = $this->getUser()->getParent()->getId();
        }

        if ($operatorId){
            $messages = $this->getDoctrine()->getRepository('AppBundle:Message')->findMessage($operatorId);
        }else{
            $messages = null;
        }

        if ($this->get('security.context')->isGranted('ROLE_ADMIN')){
            $senders = $this->getDoctrine()->getRepository('AppBundle:Message')->findUser($this->getUser()->getId());
        }else{
            $senders = null;
        }



        return array('messages' => $messages, 'senders' => $senders, 'operatorId' => $operatorId);
    }


}
