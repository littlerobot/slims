<?php

namespace Cscr\SlimsApiBundle\Controller;

use Cscr\SlimsApiBundle\Entity\AbstractSampleInstanceTemplateAttribute;
use Cscr\SlimsApiBundle\Entity\SampleInstanceTemplate;
use Cscr\SlimsApiBundle\Form\Type\SampleInstanceTemplateType;
use Cscr\SlimsApiBundle\Response\SampleInstanceTemplateCollectionResponse;
use Cscr\SlimsApiBundle\Response\SampleInstanceTemplateResponse;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
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
     * @Rest\Post("/{id}")
     *
     * @param int $id
     * @param Request $request
     *
     * @return View
     */
    public function updateAction($id, Request $request)
    {
        if (!$template = $this->getDoctrine()->getRepository('CscrSlimsApiBundle:SampleInstanceTemplate')->find($id)) {
            throw new NotFoundHttpException(sprintf("No sample instance template with ID '%d' available.", $id));
        }

        // Record original attributes so we can remove any that have been deleted
        $originalStoredAttributes = $template->getStoredAttributes()->toArray();
        $originalRemovedAttributes = $template->getRemovedAttributes()->toArray();

        $manager = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->createNamed('', new SampleInstanceTemplateType(), $template);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->removeDeletedAttributes($originalStoredAttributes, $template->getStoredAttributes()->toArray(), $manager);
            $this->removeDeletedAttributes($originalRemovedAttributes, $template->getRemovedAttributes()->toArray(), $manager);

            $manager->persist($template);
            $manager->flush();

            // ExtJS doesn't work with RESTful APIs, as far as I can see.
            // Return the object and a 200.
            return View::create(new SampleInstanceTemplateResponse($template), Response::HTTP_OK);
        }

        return View::create($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param  SampleInstanceTemplate $template
     * @param  FormTypeInterface $formType
     * @param  Request $request
     * @return View
     */
    private function processForm(SampleInstanceTemplate $template, FormTypeInterface $formType, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->createNamed('', $formType, $template);
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

    /**
     * Remove deleted {@see AbstractSampleInstanceTemplateAttribute}s from the {@see ObjectManager} if they are no
     * longer associated with the {@see SampleInstanceTemplate}.
     *
     * @param AbstractSampleInstanceTemplateAttribute[] $originalAttributes
     * @param AbstractSampleInstanceTemplateAttribute[] $newAttributes
     * @param ObjectManager $manager
     */
    private function removeDeletedAttributes(
        array $originalAttributes,
        array $newAttributes,
        ObjectManager $manager
    ) {
        // Create an ArrayCollection so we can check the contents more easily.
        $newAttributes = new ArrayCollection($newAttributes);
        foreach ($originalAttributes as $attribute) {
            if (false === $newAttributes->contains($attribute)) {
                $manager->remove($attribute);
            }
        }
    }
}
