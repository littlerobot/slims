<?php

namespace Cscr\SlimsApiBundle\Controller;

use Cscr\SlimsApiBundle\Entity\Container;
use Cscr\SlimsApiBundle\Form\Type\CreateContainerType;
use Cscr\SlimsApiBundle\Form\Type\UpdateContainerType;
use Cscr\SlimsApiBundle\Response\ContainerCollectionResponse;
use Cscr\SlimsApiBundle\Response\ContainerResponse;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
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

        return new ContainerCollectionResponse($containers);
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

        $processor = $this->get('cscr_slims_api.service.form_processor');
        return $processor->processForm($form, $container, $request);
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
        if (!$group = $this->getDoctrine()->getRepository('CscrSlimsApiBundle:Container')->find($id)) {
            throw new NotFoundHttpException(sprintf("No container with ID '%d' available.", $id));
        }

        $form = new UpdateContainerType();

        $processor = $this->get('cscr_slims_api.service.form_processor');

        return $processor->processForm($form, $group, $request);
    }
}
