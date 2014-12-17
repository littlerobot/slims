<?php

namespace Cscr\SlimsApiBundle\Controller;

use Cscr\SlimsApiBundle\Response\UserCollectionResponse;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class UsersController extends FOSRestController
{
    /**
     * @Rest\Get("/current")
     * @Rest\View()
     */
    public function getCurrentAction()
    {
        $user = $this->getUser();
        return ['data' => $user];
    }

    /**
     * @Rest\Get("")
     * @Rest\View()
     */
    public function listAction()
    {
        $users = $this->getDoctrine()->getRepository('CscrSlimsUserBundle:User')->findAll();

        return new UserCollectionResponse($users);
    }
}
