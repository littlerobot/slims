<?php

namespace Cscr\SlimsApiBundle\Tests\Response;

use Cscr\SlimsApiBundle\Entity\Container;
use Cscr\SlimsApiBundle\Entity\ResearchGroup;
use Cscr\SlimsApiBundle\Response\ContainerResponse;

class ContainerResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testSupportsContainerEntity()
    {
        $this->assertTrue(ContainerResponse::supports(new Container()));
    }

    public function testDoesNotSupportNonContainerEntity()
    {
        $this->assertFalse(ContainerResponse::supports(new ResearchGroup()));
    }
}
