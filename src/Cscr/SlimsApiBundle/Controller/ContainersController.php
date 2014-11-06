<?php

namespace Cscr\SlimsApiBundle\Controller;

use Cscr\SlimsApiBundle\Response\ExtJsResponse;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\View;

class ContainersController extends FOSRestController
{
    /**
     * @Route("")
     * @View()
     */
    public function getContainersAction()
    {
        $containers = $this->getDoctrine()->getRepository('CscrSlimsApiBundle:Container')->findRootContainers();
        return new ExtJsResponse($containers);
    }
}
