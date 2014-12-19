<?php

namespace Cscr\SlimsAppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ApplicationController extends Controller
{
    /**
     * @Route("/", name="app_home")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route("app", name="app_app")
     * @Template()
     */
    public function appAction()
    {
        return [];
    }
}
