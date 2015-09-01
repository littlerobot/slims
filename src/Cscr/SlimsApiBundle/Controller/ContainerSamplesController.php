<?php

namespace Cscr\SlimsApiBundle\Controller;

use Cscr\SlimsApiBundle\Entity\Container;
use Cscr\SlimsApiBundle\Form\Type\StoreSamplesType;
use Cscr\SlimsApiBundle\Response\SamplesResponse;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ContainerSamplesController extends FOSRestController
{
    /**
     * @param int $id Container ID.
     * @return SamplesResponse
     *
     * @Rest\Route("")
     * @Rest\View(serializerGroups={"Default"})
     */
    public function getSamplesAction($id)
    {
        $container = $this->findContainer($id);

        return new SamplesResponse($container->getSamples());
    }

    /**
     * @param int $id Container ID.
     * @param Request $request
     *
     * @Rest\Post("")
     * @Rest\View()
     * @return View
     */
    public function storeSamplesAction($id, Request $request)
    {
        $container = $this->findContainer($id);

        return $this->processForm($container, new StoreSamplesType(), $request);
    }

    /**
     * @param int $id Container ID.
     * @return Container
     * @throws NotFoundHttpException
     */
    private function findContainer($id)
    {
        if (!$container = $this->getDoctrine()->getRepository('CscrSlimsApiBundle:Container')->find($id)) {
            throw new NotFoundHttpException(sprintf("No container with ID '%d' available.", $id));
        }

        return $container;
    }

    /**
     * @param Container $container
     * @param  FormTypeInterface $formType
     * @param  Request $request
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
            return View::create(new SamplesResponse($container->getSamples()), Response::HTTP_OK);
        }

        return View::create($form, 400);
    }
}
