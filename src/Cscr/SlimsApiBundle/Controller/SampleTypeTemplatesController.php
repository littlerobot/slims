<?php

namespace Cscr\SlimsApiBundle\Controller;

use Cscr\SlimsApiBundle\Entity\SampleTypeTemplate;
use Cscr\SlimsApiBundle\Form\Type\SampleTypeTemplateType;
use Cscr\SlimsApiBundle\Response\SampleTypeTemplateCollectionResponse;
use Cscr\SlimsApiBundle\Response\SampleTypeTemplateResponse;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @Rest\Route("/{id}")
     * @Rest\View
     * @param SampleTypeTemplate $template
     * @return SampleTypeTemplateResponse
     */
    public function getSampleTypeTemplateAction(SampleTypeTemplate $template)
    {
        return new SampleTypeTemplateResponse($template);
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
        $form = new SampleTypeTemplateType();

        return $this->processForm($template, $form, $request);
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
        if (!$template = $this->getDoctrine()->getRepository('CscrSlimsApiBundle:SampleTypeTemplate')->find($id)) {
            throw new NotFoundHttpException(sprintf("No sample type template with ID '%d' available.", $id));
        }

        // Record original attributes so we can remove any that have been deleted
        $originalAttributes = $template->getAttributes()->toArray();

        $manager = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->createNamed('', new SampleTypeTemplateType(), $template);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->removeDeletedAttributes($originalAttributes, $template, $manager);

            $manager->persist($template);
            $manager->flush();

            // ExtJS doesn't work with RESTful APIs, as far as I can see.
            // Return the object and a 200.
            return View::create(new SampleTypeTemplateResponse($template), Response::HTTP_OK);
        }

        return View::create($form, Response::HTTP_BAD_REQUEST);
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

    /**
     * Remove deleted {@see SampleTypeTemplateAttribute}s from the {@see ObjectManager} if they are no longer associated
     * with the {@see SampleTypeTemplate}.
     *
     * @param array $originalAttributes
     * @param SampleTypeTemplate $template
     * @param ObjectManager $manager
     */
    private function removeDeletedAttributes(
        array $originalAttributes,
        SampleTypeTemplate $template,
        ObjectManager $manager
    ) {
        foreach ($originalAttributes as $attribute) {
            if (false === $template->getAttributes()->contains($attribute)) {
                $manager->remove($attribute);
            }
        }
    }
}
