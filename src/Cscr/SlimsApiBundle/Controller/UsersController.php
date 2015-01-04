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
        $user = new User();

        return $this->processForm($user, $request);
    }

    /**
     * @param User $user
     * @param Request $request
     *
     * @return View
     */
    private function processForm(User $user, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->createNamed('', new UserType(), $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // Make newly created users active
            if (!$manager->contains($user)) {
                $user->setActive(true);
            }

            $manager->persist($user);
            $manager->flush();

            // ExtJS doesn't work with RESTful APIs, as far as I can see.
            // Return the object and a 200.
            return View::create(new UserResponse($user), Response::HTTP_OK);
        }

        return View::create($form, 400);
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

        return $this->processForm($user, $request);
    }
}
