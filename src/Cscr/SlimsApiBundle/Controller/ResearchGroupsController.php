<?php

namespace Cscr\SlimsApiBundle\Controller;

use Cscr\SlimsApiBundle\Response\ResearchGroupResponse;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ResearchGroupsController extends Controller
{
    /**
     * @Route("")
     * @View()
     */
    public function getResearchGroupsAction()
    {
        $groups = $this->getDoctrine()->getRepository('CscrSlimsApiBundle:ResearchGroup')->findAll();
        return new ResearchGroupResponse($groups);
    }
}
