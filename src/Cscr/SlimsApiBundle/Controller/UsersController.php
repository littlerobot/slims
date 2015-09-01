<?php

namespace Cscr\SlimsApiBundle\Controller;

use Cscr\SlimsApiBundle\Form\Type\UserType;
use Cscr\SlimsApiBundle\Response\UserCollectionResponse;
use Cscr\SlimsApiBundle\Response\UserResponse;
use Cscr\SlimsUserBundle\Entity\User;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UsersController extends FOSRestController
{
    /**
     * @Rest\Get("/current")
     * @Rest\View()
     */
    public function getCurrentAction()
    {
        $user = $this->getUser();

        return new UserResponse($user);
    }

    /**
     * @Rest\Get("")
     * @Rest\View()
     *
     * @return UserCollectionResponse
     */
    public function listAction()
    {
        $users = $this->getDoctrine()->getRepository('CscrSlimsUserBundle:User')->findAll();

        return new UserCollectionResponse($users);
    }

    /**
     * @Rest\Post("")
     * @Rest\View()
     *
     * @param Request $request
     *
     * @return View
     */
    public function createAction(Request $request)
    {
        return $this->get('cscr_slims_api.service.form_processor')->processForm(new UserType(), new User(), $request);
    }

    /**
     * @Rest\Post("/{id}")
     *
     * @param int $id
     * @param Request $request
     *
     * @return View
     */
    public function updateAction($id, Request $request)
    {
        if (!$user = $this->getDoctrine()->getRepository('CscrSlimsUserBundle:User')->find($id)) {
            throw new NotFoundHttpException(sprintf("No user with ID '%d' available.", $id));
        }

        return $this->get('cscr_slims_api.service.form_processor')->processForm(new UserType(), $user, $request);
    }
}
