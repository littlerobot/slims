<?php

namespace Cscr\SlimsApiBundle\Controller;

use Cscr\SlimsApiBundle\Entity\SampleType;
use Cscr\SlimsApiBundle\Entity\SampleTypeAttribute;
use Cscr\SlimsApiBundle\Form\Type\SampleTypeType;
use Cscr\SlimsApiBundle\Response\SampleTypeCollectionResponse;
use Cscr\SlimsApiBundle\Response\SampleTypeResponse;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @Rest\Post("/{id}")
     *
     * @param int $id
     * @param Request $request
     *
     * @return View
     */
    public function updateAction($id, Request $request)
    {
        if (!$type = $this->getDoctrine()->getRepository('CscrSlimsApiBundle:SampleType')->find($id)) {
            throw new NotFoundHttpException(sprintf("No sample type with ID '%d' available.", $id));
        }

        $form = new SampleTypeType();

        // Record original attributes so we can remove any that have been deleted
        $originalAttributes = $type->getAttributes()->toArray();

        return $this->processForm($type, $form, $request, $originalAttributes);
    }

    /**
     * @param SampleType $type
     * @param FormTypeInterface $formType
     * @param Request $request
     * @param array $originalAttributes Optional, attributes that were present in the original sample type (for updates)
     *
     * @return View
     */
    private function processForm(
        SampleType $type,
        FormTypeInterface $formType,
        Request $request,
        array $originalAttributes = array()
    ) {
        $manager = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->createNamed('', $formType, $type);

        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($originalAttributes) {
                $this->removeDeletedAttributes($originalAttributes, $type, $manager);
            }

            foreach ($type->getAttributes() as $attribute) {
                if ($attribute->isDocument()) {
                    $attribute->setMimeType($this->getMimeType($attribute));
                }
            }

            $manager->persist($type);
            $manager->flush();

            // ExtJS doesn't work with RESTful APIs, as far as I can see.
            // Return the object and a 200.
            return View::create(new SampleTypeResponse($type), Response::HTTP_OK);
        }

        return View::create($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Remove deleted {@see SampleTypeAttribute}s from the {@see ObjectManager} if they are no longer associated
     * with the {@see SampleType}.
     *
     * @param array $originalAttributes
     * @param SampleType $type
     * @param ObjectManager $manager
     */
    private function removeDeletedAttributes(
        array $originalAttributes,
        SampleType $type,
        ObjectManager $manager
    ) {
        foreach ($originalAttributes as $attribute) {
            if (false === $type->getAttributes()->contains($attribute)) {
                $manager->remove($attribute);
            }
        }
    }

    /**
     * Get the MIME type for an uploaded {@SampleTypeAttribute} which is a document.
     *
     * @param SampleTypeAttribute $attribute
     * @return string|null MIME type or null if the attribute is not a document.
     */
    private function getMimeType(SampleTypeAttribute $attribute)
    {
        if (!$attribute->isDocument()) {
            return null;
        }

        $path = tempnam(sys_get_temp_dir(), 'upload');
        $file = new Filesystem();
        $file->dumpFile($path, $attribute->getBinaryContent());
        $guesser = MimeTypeGuesser::getInstance();
        $mimeType = $guesser->guess($path);
        $file->remove($path);

        return $mimeType;
    }
}
