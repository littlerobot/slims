<?php

namespace Cscr\SlimsApiBundle\Controller;

use Cscr\SlimsApiBundle\Response\SamplesResponse;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SamplesController extends FOSRestController
{
    /**
     * @param int $id
     * @return SamplesResponse
     *
     * @Rest\Route("")
     * @Rest\View()
     */
    public function getSamplesAction($id)
    {
        if (!$container = $this->getDoctrine()->getRepository('CscrSlimsApiBundle:Container')->find($id)) {
            throw new NotFoundHttpException(sprintf("No container with ID '%d' available.", $id));
        }

        return new SamplesResponse($container->getSamples());
    }
}
