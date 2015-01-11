<?php

namespace Cscr\SlimsApiBundle\Controller;

use Cscr\SlimsApiBundle\Entity\SampleTypeTemplate;
use Cscr\SlimsApiBundle\Form\Type\CreateSampleTypeTemplateType;
use Cscr\SlimsApiBundle\Response\SampleTypeTemplateCollectionResponse;
use Cscr\SlimsApiBundle\Response\SampleTypeTemplateResponse;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SampleTypeTemplatesController extends FOSRestController
{
    /**
     * @Rest\Route("")
     * @Rest\View()
     */
    public function getSampleTypeTemplatesAction()
    {
        $templates = $this->getDoctrine()->getRepository('CscrSlimsApiBundle:SampleTypeTemplate')->findAll();
        return new SampleTypeTemplateCollectionResponse($templates);
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
        $template = new SampleTypeTemplate();
        $form = new CreateSampleTypeTemplateType();

        return $this->processForm($template, $form, $request);
    }

    /**
     * @param  SampleTypeTemplate $template
     * @param  FormTypeInterface $form
     * @param  Request $request
     * @return View
     */
    private function processForm(SampleTypeTemplate $template, FormTypeInterface $form, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->createNamed('', $form, $template);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager->persist($template);
            $manager->flush();

            // ExtJS doesn't work with RESTful APIs, as far as I can see.
            // Return the object and a 200.
            return View::create(new SampleTypeTemplateResponse($template), Response::HTTP_OK);
        }

        return View::create($form, Response::HTTP_BAD_REQUEST);
    }
}
