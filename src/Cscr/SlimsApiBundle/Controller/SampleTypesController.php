<?php

namespace Cscr\SlimsApiBundle\Controller;

use Cscr\SlimsApiBundle\Entity\SampleType;
use Cscr\SlimsApiBundle\Form\Type\SampleTypeType;
use Cscr\SlimsApiBundle\Response\SampleTypeCollectionResponse;
use Cscr\SlimsApiBundle\Response\SampleTypeResponse;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SampleTypesController extends FOSRestController
{
    /**
     * @Rest\Route("")
     * @Rest\View()
     */
    public function getSampleTypesAction()
    {
        $templates = $this->getDoctrine()->getRepository('CscrSlimsApiBundle:SampleType')->findAll();
        return new SampleTypeCollectionResponse($templates);
    }

    /**
     * @Rest\Route("/{id}")
     * @Rest\View
     * @param SampleType $type
     * @return SampleTypeResponse
     */
    public function getSampleTypeAction(SampleType $type)
    {
        return new SampleTypeResponse($type);
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
        $template = new SampleType();
        $form = new SampleTypeType();

        return $this->processForm($template, $form, $request);
    }

    /**
     * @param  SampleType $type
     * @param  FormTypeInterface $form
     * @param  Request $request
     * @return View
     */
    private function processForm(SampleType $type, FormTypeInterface $form, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->createNamed('', $form, $type);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager->persist($type);
            $manager->flush();

            // ExtJS doesn't work with RESTful APIs, as far as I can see.
            // Return the object and a 200.
            return View::create(new SampleTypeResponse($type), Response::HTTP_OK);
        }

        return View::create($form, Response::HTTP_BAD_REQUEST);
    }
}
