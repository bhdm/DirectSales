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
 * @Route("/project")
 */
class StatsController extends Controller
{
    /**
     * @Security("has_role('ROLE_AGENT')")
     * @Route("/", name="stats_list")
     * @Template()
     */
    public function listAction()
    {
        return array();
    }
}