<?php

namespace Cscr\SlimsApiBundle\Tests\Service;

use Cscr\SlimsApiBundle\Entity\Container;
use Cscr\SlimsApiBundle\Form\Type\CreateContainerType;
use Cscr\SlimsApiBundle\Service\FormProcessor;
use Cscr\SlimsApiBundle\Service\ResponseRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FormProcessorTest extends \PHPUnit_Framework_TestCase
{
    /** @var ObjectManager|\PHPUnit_Framework_MockObject_MockObject */
    private $manager;

    /** @var ResponseRepository|\PHPUnit_Framework_MockObject_MockObject */
    private $repository;

    /** @var FormFactoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $factory;

    /** @var Request|\PHPUnit_Framework_MockObject_MockObject */
    private $request;

    /** @var FormInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $form;

    /** @var FormProcessor */
    private $SUT;

    public function setUp()
    {
        $this->manager = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
            ->getMockForAbstractClass();

        $this->repository = $this->getMockBuilder('Cscr\SlimsApiBundle\Service\ResponseRepository')
            ->getMock();

        $this->factory = $this->getMockBuilder('Symfony\Component\Form\FormFactoryInterface')
            ->setMethods(['createNamed'])
            ->getMockForAbstractClass();

        $this->form = $this->getMockBuilder('Symfony\Component\Form\FormInterface')
            ->setMethods(['isValid'])
            ->getMockForAbstractClass();

        $this->factory
            ->method('createNamed')
            ->will($this->returnValue($this->form));

        $this->request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->getMock();

        $this->SUT = new FormProcessor($this->manager, $this->repository, $this->factory);
    }

    public function testValidForm()
    {
        $this->form
            ->method('isValid')
            ->will($this->returnValue(true));

        $response = $this->SUT->processForm(new CreateContainerType(), new Container(), $this->request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testInvalidForm()
    {
        $this->form
            ->method('isValid')
            ->will($this->returnValue(false));

        $response = $this->SUT->processForm(new CreateContainerType(), new Container(), $this->request);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
