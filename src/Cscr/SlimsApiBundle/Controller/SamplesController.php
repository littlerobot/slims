<?php

namespace Cscr\SlimsApiBundle\Controller;

use Cscr\SlimsApiBundle\Response\FilteredSamplesResponse;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

class SamplesController extends FOSRestController
{
    /**
     * @Rest\Get("")
     * @Rest\QueryParam(name="name", nullable=true)
     * @Rest\QueryParam(name="type", nullable=true)
     * @Rest\QueryParam(name="user", nullable=true)
     * @Rest\QueryParam(name="container", nullable=true)
     * @Rest\QueryParam(name="stored_start", requirements="[0-9]{2}/[0-9]{2}/[0-9]{4}", nullable=true)
     * @Rest\QueryParam(name="stored_end", requirements="[0-9]{2}/[0-9]{2}/[0-9]{4}", nullable=true)
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

        return new FilteredSamplesResponse($results);
    }

    /**
     * @Rest\Route("/dummy")
     */
    public function dummyAction()
    {
        $content = <<<HEREDOC
{
  "success": true,
  "data": [
    {
      "container": 5,
      "containerName": "Floor 4",
      "row": 7,
      "column": 7,
      "colour": "#451322",
      "sampleType": 22,
      "sampleTypeName": "Mouse Mickey",
      "name": "Sample Name",
      "sampleTemplate": 31,
      "sampleTemplateName": "Template Name",
      "attributes": [
        {
          "id": 12,
          "label": "City",
          "type": "text",
          "value": "Los Angeles"
        },
        {
          "id": 1,
          "label": "Attached map",
          "type": "file",
          "filename": "Manufacturer information.pdf",
          "mime_type": "application/pdf",
          "value": "<base 64 encoded file content>"
        }
      ]
    }
  ]
}
HEREDOC;

        return new Response($content);
    }
}
