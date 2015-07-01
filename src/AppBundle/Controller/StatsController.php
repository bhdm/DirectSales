<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Project;
use AppBundle\Form\ProjectType;

/**
 * Class StatsController
 * @package AppBundle\Controller
 * @Route("/stats")
 */
class StatsController extends Controller
{

    /**
     * @Security("has_role('ROLE_AGENT')")
     * @Route("/", name="stats_projects_list")
     * @Template()
     */
    public function projectsAction()
    {
        if ( $this->get('security.context')->isGranted('ROLE_ADMIN')){
            $projects = $this->getDoctrine()->getRepository('AppBundle:Project')->findAll();
        }else{
            $projects = $this->getUser()->getProjects();
        }
        return array('projects' => $projects);
    }

    /**
     * @Security("has_role('ROLE_AGENT')")
     * @Route("/project/{projectId}", name="stats_project")
     * @Template()
     */
    public function eventsAction($projectId)
    {
        $project = $this->getDoctrine()->getRepository('AppBundle:Project')->findOneById($projectId);
        return array('project' => $project);
    }

    /**
     * @Security("has_role('ROLE_AGENT')")
     * @Route("/agents", name="stats_agents_list")
     * @Template()
     */
    public function agentsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:User');
        $items = $this->getDoctrine()->getRepository('AppBundle:User')->findById($this->getUser()->getId());
        return array('items' => $items);
    }


}