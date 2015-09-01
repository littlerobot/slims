<?php

namespace Cscr\SlimsApiBundle\Tests\Service;

use Cscr\SlimsApiBundle\Entity\Downloadable;
use Cscr\SlimsApiBundle\Service\DownloadableEntityUrlGeneratorInterface;
use Cscr\SlimsApiBundle\Service\DownloadableEntityUrlGeneratorRegistry;

class DownloadableEntityUrlGeneratorRegistryTest extends \PHPUnit_Framework_TestCase
{
    /** @var Downloadable|\PHPUnit_Framework_MockObject_MockObject */
    private $downloadable;

    /** @var DownloadableEntityUrlGeneratorRegistry */
    private $SUT;

    public function setUp()
    {
        $this->downloadable = $this->getMock('Cscr\SlimsApiBundle\Entity\Downloadable');
        $this->SUT = new DownloadableEntityUrlGeneratorRegistry();
    }

    public function testMatchingGeneratorIsReturned()
    {
        $nonMatching = $this->buildNonMatchingGenerator();
        $matching = $this->buildMatchingGenerator();

        $this->SUT->addUrlGenerator($nonMatching);
        $this->SUT->addUrlGenerator($matching);

        $this->assertEquals($matching, $this->SUT->getGeneratorFor($this->downloadable));
    }

    /**
     * @return DownloadableEntityUrlGeneratorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function buildNonMatchingGenerator()
    {
        $nonMatching = $this->getBaseMockedGenerator();

        $nonMatching
            ->method('canGenerateFor')
            ->will($this->returnValue(false));

        return $nonMatching;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getBaseMockedGenerator()
    {
        $generator = $this->getMockBuilder('Cscr\SlimsApiBundle\Service\DownloadableEntityUrlGeneratorInterface')
            ->setMethods(['canGenerateFor'])
            ->getMockForAbstractClass();

        return $generator;
    }

    /**
     * @return DownloadableEntityUrlGeneratorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function buildMatchingGenerator()
    {
        $matching = $this->getBaseMockedGenerator();

        $matching
            ->method('canGenerateFor')
            ->will($this->returnValue(true));

        return $matching;
    }

    public function testNoGeneratorReturnedWhenThereIsNoMatch()
    {
        $nonMatching = $this->buildNonMatchingGenerator();

        $this->SUT->addUrlGenerator($nonMatching);

        $this->assertNull($this->SUT->getGeneratorFor($this->downloadable));
    }

    public function testFirstGeneratorReturnedWhenThereAreMultipleMatches()
    {
        $firstMatching = $this->buildMatchingGenerator();
        $secondMatching = $this->buildMatchingGenerator();

        $this->SUT->addUrlGenerator($firstMatching);
        $this->SUT->addUrlGenerator($secondMatching);

        $this->assertEquals($firstMatching, $this->SUT->getGeneratorFor($this->downloadable));
    }
}
