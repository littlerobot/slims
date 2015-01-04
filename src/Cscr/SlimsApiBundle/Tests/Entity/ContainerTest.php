<?php

namespace Cscr\SlimsApiBundle\Tests\Entity;

use Cscr\SlimsApiBundle\Entity\Container;
use Cscr\SlimsApiBundle\Tests\Builder\ContainerBuilder;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnsCorrectTotalSampleCapacity()
    {
        $builder = new ContainerBuilder();

        $parent = $builder->withStores(Container::STORES_CONTAINERS)
            ->build();

        $child1 = $builder->withStores(Container::STORES_SAMPLES)
            ->withRows(10)
            ->withColumns(10)
            ->build();

        $child2 = $builder->withStores(Container::STORES_SAMPLES)
            ->withRows(1)
            ->withColumns(1)
            ->build();

        $parent->storeContainerInside($child1)
            ->storeContainerInside($child2);

        $this->assertEquals((10 * 10) + (1 * 1), $parent->getTotalSampleCapacity());
    }
}
