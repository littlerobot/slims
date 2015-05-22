<?php

namespace Cscr\SlimsApiBundle\Controller;

use Cscr\SlimsApiBundle\Entity\Container;
use Cscr\SlimsApiBundle\Entity\Sample;
use Cscr\SlimsApiBundle\Form\Type\StoreSampleType;
use Cscr\SlimsApiBundle\Response\SamplesResponse;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SamplesController extends FOSRestController
{
    /**
     * @param int $id Container ID.
     * @return SamplesResponse
     *
     * @Rest\Route("")
     * @Rest\View(serializerGroups={"Default"})
     */
    public function getSamplesAction($id)
    {
        $container = $this->findContainer($id);

        return new SamplesResponse($container->getSamples());
    }

    /**
     * @param int $id Container ID.
     * @param Request $request
     *
     * @Rest\Post("")
     * @Rest\View()
     * @return View
     */
    public function storeSampleAction($id, Request $request)
    {
        $container = $this->findContainer($id);
        $sample = new Sample();
        $sample->setContainer($container);

        return $this->processForm($sample, new StoreSampleType(), $request);
    }

    /**
     * @param int $id Container ID.
     * @return Container
     * @throws NotFoundHttpException
     */
    private function findContainer($id)
    {
        if (!$container = $this->getDoctrine()->getRepository('CscrSlimsApiBundle:Container')->find($id)) {
            throw new NotFoundHttpException(sprintf("No container with ID '%d' available.", $id));
        }

        return $container;
    }

    /**
     * @param  Sample $sample
     * @param  FormTypeInterface $formType
     * @param  Request $request
     * @return View
     */
    private function processForm(Sample $sample, FormTypeInterface $formType, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        $form = $this->get('form.factory')->createNamed('', $formType, $sample);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // FIXME: Should be set in a command
            $sample->setState(Sample::STATE_STORED);
            $manager->persist($sample);
            $manager->flush();

            // ExtJS doesn't work with RESTful APIs, as far as I can see.
            // Return the object and a 200.
            return View::create(new SamplesResponse($sample->getContainer()->getSamples()), Response::HTTP_OK);
        }

        return View::create($form, 400);
    }
}
