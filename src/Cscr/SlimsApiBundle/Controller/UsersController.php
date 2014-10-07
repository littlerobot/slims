<?php

namespace Cscr\SlimsApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\View;

class UsersController extends FOSRestController
{
    /**
     * @Route("/current")
     * @View()
     */
    public function getCurrentAction()
    {
        $user = $this->getUser();
        return ['data' => $user];
    }
}
