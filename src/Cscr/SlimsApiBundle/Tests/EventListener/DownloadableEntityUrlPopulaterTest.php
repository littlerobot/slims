<?php

namespace Cscr\SlimsApiBundle\Tests\EventListener;

use Cscr\SlimsApiBundle\Entity\SampleTypeAttribute;
use Cscr\SlimsApiBundle\EventListener\DownloadableEntityUrlPopulater;
use Cscr\SlimsApiBundle\Service\DownloadableEntityUrlGeneratorInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Cscr\SlimsApiBundle\Service\DownloadableEntityUrlGeneratorRegistry;

class DownloadableEntityUrlPopulaterTest extends \PHPUnit_Framework_TestCase
{
    /** @var LifecycleEventArgs|\PHPUnit_Framework_MockObject_MockObject */
    private $event;

    /** @var DownloadableEntityUrlGeneratorRegistry|\PHPUnit_Framework_MockObject_MockObject */
    private $registry;

    /** @var DownloadableEntityUrlPopulater */
    private $SUT;

    public function setUp()
    {
        $this->registry = $this->getMock('Cscr\SlimsApiBundle\Service\DownloadableEntityUrlGeneratorRegistry');

        $this->event = $this->getMockBuilder('Doctrine\ORM\Event\LifecycleEventArgs')
            ->disableOriginalConstructor()
            ->getMock();

        $this->SUT = new DownloadableEntityUrlPopulater($this->registry);
    }

    public function testDoesNothingIfTheEntityIsNotADownloadable()
    {
        $entity = $this->getMock('Cscr\SlimsApiBundle\Entity\Container');
        $entity
            ->expects($this->never())
            ->method('setUrl');

        $this->event
            ->method('getEntity')
            ->will($this->returnValue($entity));

        $this->SUT->postLoad($this->event);
    }

    public function testDoesNothingIfTheEntityIsNotDownloadable()
    {
        $entity = $this->getMock('Cscr\SlimsApiBundle\Entity\SampleTypeAttribute');
        $entity
            ->expects($this->never())
            ->method('setUrl');

        $this->event
            ->method('getEntity')
            ->will($this->returnValue($entity));

        $this->SUT->postLoad($this->event);
    }

    public function testSetsTheUrlIfTheEntityIsADownloadable()
    {
        /** @var DownloadableEntityUrlGeneratorInterface|\PHPUnit_Framework_MockObject_MockObject $generator */
        $generator = $this->getMockBuilder('Cscr\SlimsApiBundle\Service\DownloadableEntityUrlGeneratorInterface')
            ->setMethods(['canGenerateFor', 'generateUrl'])
            ->getMockForAbstractClass();

        $generator
            ->method('canGenerateFor')
            ->will($this->returnValue(true));

        $generator
            ->method('generateUrl')
            ->will($this->returnValue('/foo/bar'));

        $this->registry
            ->method('getGeneratorFor')
            ->will($this->returnValue($generator));

        /** @var SampleTypeAttribute|\PHPUnit_Framework_MockObject_MockObject $entity */
        $entity = $this->getMock('Cscr\SlimsApiBundle\Entity\SampleTypeAttribute');
        $entity
            ->method('getFilename')
            ->will($this->returnValue('foo.bar'));

        $entity
            ->expects($this->once())
            ->method('setUrl');

        $this->event
            ->method('getEntity')
            ->will($this->returnValue($entity));

        $this->SUT->postLoad($this->event);
    }
}
