<?php

namespace Cscr\SlimsApiBundle\Controller;

use Cscr\SlimsApiBundle\Entity\SampleType;
use Cscr\SlimsApiBundle\Response\SampleTypeResponse;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class SampleTypesController extends FOSRestController
{
    /**
     * @Rest\Route("")
     * @Rest\View()
     */
    public function getSampleTypeAction()
    {
        $templates = $this->getDoctrine()->getRepository('CscrSlimsApiBundle:SampleType')->findAll();
        return new SampleTypeResponse($templates);
    }

    /**
     * @Rest\Route("/{id}")
     * @Rest\View
     * @param SampleType $type
     * @return SampleTypeResponse
     */
    public function getSampleTypeTemplateAction(SampleType $type)
    {
        return new SampleTypeResponse($type);
    }
}
