<?php

namespace Cscr\SlimsApiBundle\Tests\Entity;

use Cscr\SlimsApiBundle\Entity\Sample;

class SampleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \RuntimeException
     */
    public function testAPositionMustIncludeAColon()
    {
        $sample = new Sample();
        $sample->setPosition('11');
    }

    public function testRowAndColumnIsSetFromPosition()
    {
        $sample = new Sample();
        $sample->setPosition('1:2');
        $this->assertEquals(1, $sample->getRow());
        $this->assertEquals(2, $sample->getColumn());
    }
}
