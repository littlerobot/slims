<?php

namespace Cscr\SlimsApiBundle\Tests\Entity;

use Cscr\SlimsApiBundle\Entity\Container;
use Cscr\SlimsApiBundle\Entity\Sample;
use Cscr\SlimsApiBundle\Tests\Builder\ContainerBuilder;
use Cscr\SlimsApiBundle\ValueObject\SamplePosition;

class SampleTest extends \PHPUnit_Framework_TestCase
{
    public function testRowAndColumnIsSetFromPosition()
    {
        $sample = new Sample();
        $sample->setPosition(SamplePosition::fromCoordinates('1:2'));
        $this->assertEquals(1, $sample->getRow());
        $this->assertEquals(2, $sample->getColumn());
    }

    /**
     * @dataProvider getIndexDataProvider
     *
     * @param int            $containerRows
     * @param int            $containerColumns
     * @param SamplePosition $position
     * @param int            $index
     */
    public function testGetIndex($containerRows, $containerColumns, SamplePosition $position, $index)
    {
        $container = new Container();
        $container
            ->setRows($containerRows)
            ->setColumns($containerColumns);
        $sample = new Sample();
        $sample->setPosition($position);
        $container->addSample($sample);

        $this->assertEquals($index, $sample->getIndex());
    }


    public function getIndexDataProvider()
    {
        // Container rows, container columns, position, index
        return [
            [9, 9, SamplePosition::fromCoordinates('0:0'), 1],
            [9, 9, SamplePosition::fromCoordinates('0:1'), 2],
            [9, 9, SamplePosition::fromCoordinates('0:8'), 9],
            [9, 9, SamplePosition::fromCoordinates('1:0'), 10],
            [10, 10, SamplePosition::fromCoordinates('1:0'), 11],
        ];
    }

    /**
     * @expectedException \LogicException
     */
    public function testCannotGetIndexForSampleWithNoContainer()
    {
        $sample = new Sample();
        $sample->getIndex();
    }

    public function testGetHierarchy()
    {
        $parent = (new ContainerBuilder())
            ->withName('Parent')
            ->build();

        $child = (new ContainerBuilder())
            ->withName('Child')
            ->withRows(9)
            ->withColumns(9)
            ->withStores(Container::STORES_SAMPLES)
            ->build();

        $sample = (new Sample())
            ->setPosition(SamplePosition::fromCoordinates('1:2'));

        $child->addSample($sample)
              ->setParent($parent);

        $this->assertEquals('Parent > Child [12]', $sample->getHierarchy());
    }
}
