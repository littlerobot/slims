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
        $group = new ResearchGroup();

        return $this->processForm($group, $request);
    }

    /**
     * @Rest\Post("/{id}")
     *
     * @param int     $id
     * @param Request $request
     *
     * @return View
     */
    public function updateAction($id, Request $request)
    {
        if (!$group = $this->getDoctrine()->getRepository('CscrSlimsApiBundle:ResearchGroup')->find($id)) {
            throw new NotFoundHttpException(sprintf("No research group with ID '%d' available.", $id));
        }

        return $this->processForm($group, $request);
    }

    /**
     * @param  ResearchGroup $group
     * @param  Request       $request
     * @return View
     */
    private function processForm(ResearchGroup $group, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->createNamed('', new ResearchGroupType(), $group);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager->persist($group);
            $manager->flush();

            // ExtJS doesn't work with RESTful APIs, as far as I can see.
            // Return the object and a 200.
            return View::create(new ResearchGroupResponse($group), Response::HTTP_OK);
        }

        return View::create($form, 400);
    }
}
