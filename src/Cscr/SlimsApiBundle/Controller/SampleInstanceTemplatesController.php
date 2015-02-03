<?php

namespace Cscr\SlimsApiBundle\Controller;

use Cscr\SlimsApiBundle\Entity\SampleInstanceTemplate;
use Cscr\SlimsApiBundle\Form\Type\SampleInstanceTemplateType;
use Cscr\SlimsApiBundle\Response\SampleInstanceTemplateCollectionResponse;
use Cscr\SlimsApiBundle\Response\SampleInstanceTemplateResponse;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SampleInstanceTemplatesController extends FOSRestController
{
    /**
     * @Rest\Route("")
     * @Rest\View()
     */
    public function getSampleInstanceTemplatesAction()
    {
        $templates = $this->getDoctrine()->getRepository('CscrSlimsApiBundle:SampleInstanceTemplate')->findAll();
        return new SampleInstanceTemplateCollectionResponse($templates);
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
        $template = new SampleInstanceTemplate();
        $form = new SampleInstanceTemplateType();

        return $this->processForm($template, $form, $request);
    }

    /**
     * @param  SampleInstanceTemplate $template
     * @param  FormTypeInterface $form
     * @param  Request $request
     * @return View
     */
    private function processForm(SampleInstanceTemplate $template, FormTypeInterface $form, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->createNamed('', $form, $template);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager->persist($template);
            $manager->flush();

            // ExtJS doesn't work with RESTful APIs, as far as I can see.
            // Return the object and a 200.
            return View::create(new SampleInstanceTemplateResponse($template), Response::HTTP_OK);
        }

        return View::create($form, Response::HTTP_BAD_REQUEST);
    }
}
