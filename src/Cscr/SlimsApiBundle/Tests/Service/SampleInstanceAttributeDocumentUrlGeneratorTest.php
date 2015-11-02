<?php

namespace Cscr\SlimsApiBundle\Tests\Service;

use Cscr\SlimsApiBundle\Entity\SampleInstanceAttribute;
use Cscr\SlimsApiBundle\Service\SampleInstanceAttributeDocumentUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class SampleInstanceAttributeDocumentUrlGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /** @var Router|\PHPUnit_Framework_MockObject_MockObject */
    private $router;

    /** @var SampleInstanceAttributeDocumentUrlGenerator */
    private $SUT;

    public function setUp()
    {
        $this->router = $this->getMockBuilder('Symfony\Bundle\FrameworkBundle\Routing\Router')
                             ->disableOriginalConstructor()
                             ->getMock();

        $this->SUT = new SampleInstanceAttributeDocumentUrlGenerator($this->router);
    }

    public function testItCanGenerateAUrlForASampleInstanceAttribute()
    {
        $namespace = get_class(new SampleInstanceAttribute());

        $this->assertTrue($this->SUT->canGenerateFor($namespace));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAnAttributeWithNoFilenameThrowsAnException()
    {
        $this->SUT->generateUrl(1, '');
    }

    public function testTheRouterGeneratesAFilename()
    {
        $url = 'Foo/Bar/Baz';

        $this->router->method('generate')
                     ->will($this->returnValue($url));

        $this->assertEquals($url, $this->SUT->generateUrl(1, 'Foo'));
    }
}
