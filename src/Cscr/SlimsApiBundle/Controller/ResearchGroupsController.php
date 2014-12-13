<?php

namespace Cscr\SlimsApiBundle\Controller;

use Cscr\SlimsApiBundle\Entity\ResearchGroup;
use Cscr\SlimsApiBundle\Form\ResearchGroupType;
use Cscr\SlimsApiBundle\Response\ResearchGroupCollectionResponse;
use Cscr\SlimsApiBundle\Response\ResearchGroupResponse;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResearchGroupsController extends Controller
{
    /**
     * @Rest\Route("")
     * @Rest\View()
     */
    public function getAction()
    {
        $groups = $this->getDoctrine()->getRepository('CscrSlimsApiBundle:ResearchGroup')->findAll();
        return new ResearchGroupCollectionResponse($groups);
    }

    /**
     * @Rest\Route("")
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function postAction(Request $request)
    {
        $group = new ResearchGroup();

        if ($params = $request->request->get('params')) {
            $id = $params['id'];
            $repository = $this->getDoctrine()->getRepository('CscrSlimsApiBundle:ResearchGroup');

            if ($existingGroup = $repository->find($id)) {
                $group = $existingGroup;
            }
        }

        return $this->processForm($group, $request);
    }

    private function processForm(ResearchGroup $group, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->createNamed('params', new ResearchGroupType(), $group);
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
