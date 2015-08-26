<?php

namespace Cscr\SlimsApiBundle\Controller;

use Cscr\SlimsApiBundle\Entity\ResearchGroup;
use Cscr\SlimsApiBundle\Form\Type\ResearchGroupType;
use Cscr\SlimsApiBundle\Response\ResearchGroupCollectionResponse;
use Cscr\SlimsApiBundle\Response\ResearchGroupResponse;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResearchGroupsController extends Controller
{
    /**
     * @Rest\Get("")
     * @Rest\View()
     */
    public function listAction()
    {
        $groups = $this->getDoctrine()->getRepository('CscrSlimsApiBundle:ResearchGroup')->findAll();

        return new ResearchGroupCollectionResponse($groups);
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
        return $this->get('cscr_slims_api.service.form_processor')->processForm(
            new ResearchGroupType(),
            new ResearchGroup(),
            $request
        );
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
        if (!$group = $this->getDoctrine()->getRepository('CscrSlimsApiBundle:ResearchGroup')->find($id)) {
            throw new NotFoundHttpException(sprintf("No research group with ID '%d' available.", $id));
        }

        return $this->get('cscr_slims_api.service.form_processor')->processForm(
            new ResearchGroupType(),
            $group,
            $request
        );
    }
}
