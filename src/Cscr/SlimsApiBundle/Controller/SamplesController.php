<?php

namespace Cscr\SlimsApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;

class SamplesController extends FOSRestController
{
    /**
     * @Rest\Get("")
     * @Rest\QueryParam(name="name", nullable=true)
     * @Rest\QueryParam(name="type", nullable=true)
     * @Rest\QueryParam(name="user", nullable=true)
     * @Rest\QueryParam(name="container", nullable=true)
     * @Rest\QueryParam(name="stored_start", requirements="[0-9]{4}-[0-9]{2}-[0-9]{2}", nullable=true)
     * @Rest\QueryParam(name="stored_end", requirements="[0-9]{4}-[0-9]{2}-[0-9]{2}", nullable=true)
     *
     * @param ParamFetcher $paramFetcher
     * @return View
     */
    public function filterAction(ParamFetcher $paramFetcher)
    {
        $results = $this->getDoctrine()
            ->getRepository('CscrSlimsApiBundle:Sample')
            ->filter(
                $paramFetcher->get('name'),
                $paramFetcher->get('type'),
                $paramFetcher->get('user'),
                $paramFetcher->get('container'),
                $paramFetcher->get('stored_start'),
                $paramFetcher->get('stored_end')
            );

        return View::create($results);
    }
}
