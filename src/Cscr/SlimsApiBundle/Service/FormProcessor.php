<?php

namespace Cscr\SlimsApiBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FormProcessor
{
    /**
     * @var ObjectManager
     */
    private $manager;
    /**
     * @var ResponseRepository
     */
    private $repository;
    /**
     * @var FormFactoryInterface
     */
    private $factory;

    /**
     * @param ObjectManager $manager
     * @param ResponseRepository $repository
     * @param FormFactoryInterface $factory
     */
    public function __construct(ObjectManager $manager, ResponseRepository $repository, FormFactoryInterface $factory)
    {
        $this->manager = $manager;
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @param FormTypeInterface $formType
     * @param $entity
     * @param Request $request
     * @return View
     */
    public function processForm(FormTypeInterface $formType, $entity, Request $request)
    {
        $form = $this->factory->createNamed('', $formType, $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->manager->persist($entity);
            $this->manager->flush();

            // ExtJS doesn't work with RESTful APIs, as far as I can see.
            // Return the object and a 200.
            $response = $this->repository->getFor($entity);

            return View::create($response, Response::HTTP_OK);
        }

        return View::create($form, Response::HTTP_BAD_REQUEST);
    }
}
