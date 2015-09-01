<?php

namespace Cscr\SlimsApiBundle\Tests\Service;

use Cscr\SlimsApiBundle\Entity\SampleTypeAttribute;
use Cscr\SlimsApiBundle\Service\SampleTypeAttributeDocumentUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class SampleTypeTemplateDocumentUrlGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /** @var Router|\PHPUnit_Framework_MockObject_MockObject */
    private $router;

    /** @var SampleTypeAttributeDocumentUrlGenerator */
    private $SUT;

    public function setUp()
    {
        $this->router = $this->getMockBuilder('Symfony\Bundle\FrameworkBundle\Routing\Router')
            ->disableOriginalConstructor()
            ->getMock();

        $this->SUT = new SampleTypeAttributeDocumentUrlGenerator($this->router);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAnExceptionIsThrownIfThereIsNoFilename()
    {
        $this->SUT->generateUrl(1, '');
    }

    public function testAUrlIsGenerated()
    {
        $url = '/foo/bar/baz';

        $this->router
            ->method('generate')
            ->will($this->returnValue($url));

        $this->assertEquals($url, $this->SUT->generateUrl(1, 'Foo'));
    }

    public function testCanGenerateCoverageForASampleTypeAttribute()
    {
        $namespace = get_class(new SampleTypeAttribute());
        $this->assertTrue($this->SUT->canGenerateFor($namespace));
    }
}
