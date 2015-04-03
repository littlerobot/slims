<?php

namespace Cscr\SlimsApiBundle\Controller;

use Cscr\SlimsApiBundle\Entity\Container;
use Cscr\SlimsApiBundle\Form\Type\CreateContainerType;
use Cscr\SlimsApiBundle\Form\Type\UpdateContainerType;
use Cscr\SlimsApiBundle\Response\ContainerResponse;
use Cscr\SlimsApiBundle\Response\ExtJsResponse;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ContainersController extends FOSRestController
{
    /**
     * @Rest\Route("")
     * @Rest\View()
     */
    public function getContainersAction()
    {
        $containers = $this->getDoctrine()->getRepository('CscrSlimsApiBundle:Container')->findRootContainers();

        return new ExtJsResponse($containers);
    }

    /**
     * @Rest\Post("")
     *
     * @param Request $request
     *
     * @return View
     */
    public function createAction(Request $request)
    {
        $container = new Container();
        $form = new CreateContainerType();

        return $this->processForm($container, $form, $request);
    }

    /**
     * @Rest\Post("/{id}")
     *
     * @param int     $id
     * @param Request $request
     *
     * @return View
     */
    public function updateAction($id, Request $request)
    {
        if (!$group = $this->getDoctrine()->getRepository('CscrSlimsApiBundle:Container')->find($id)) {
            throw new NotFoundHttpException(sprintf("No container with ID '%d' available.", $id));
        }

        $form = new UpdateContainerType();

        return $this->processForm($group, $form, $request);
    }

    /**
     * @param  Container         $container
     * @param  FormTypeInterface $formType
     * @param  Request           $request
     * @return View
     */
    private function processForm(Container $container, FormTypeInterface $formType, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->createNamed('', $formType, $container);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager->persist($container);
            $manager->flush();

            // ExtJS doesn't work with RESTful APIs, as far as I can see.
            // Return the object and a 200.
            return View::create(new ContainerResponse($container), Response::HTTP_OK);
        }

        return View::create($form, 400);
    }
}
