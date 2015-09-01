<?php

namespace Cscr\SlimsApiBundle\Tests\Entity;

use Cscr\SlimsApiBundle\Entity\Container;
use Cscr\SlimsApiBundle\Tests\Builder\ContainerBuilder;
use Cscr\SlimsApiBundle\Tests\Builder\SampleBuilder;

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

    public function testRemainingCapacityUpdatedWhenASampleIsAdded()
    {
        $container = (new ContainerBuilder())
            ->withStores(Container::STORES_SAMPLES)
            ->withColumns(1)
            ->withRows(1)
            ->build();

        $this->assertEquals(1, $container->getSampleRemainingCapacity());

        $container->addSample((new SampleBuilder())->build());

        $this->assertEquals(0, $container->getSampleRemainingCapacity());
    }

    public function testRemainingCapacityForChildContainers()
    {
        $parent = (new ContainerBuilder())
            ->withStores(Container::STORES_CONTAINERS)
            ->withColumns(2)
            ->withRows(1)
            ->build();

        $childA = (new ContainerBuilder())
            ->withStores(Container::STORES_SAMPLES)
            ->withRows(1)
            ->withColumns(1)
            ->build();

        $childB = (new ContainerBuilder())
            ->withStores(Container::STORES_SAMPLES)
            ->withRows(2)
            ->withColumns(2)
            ->build();

        $parent
            ->storeContainerInside($childA)
            ->storeContainerInside($childB);

        $this->assertEquals(5, $parent->getTotalSampleCapacity());
    }

    public function testTotalStoredSamplesIncludesChildContainers()
    {
        $parent = (new ContainerBuilder())
            ->withStores(Container::STORES_CONTAINERS)
            ->withColumns(2)
            ->withRows(1)
            ->build();

        $childA = (new ContainerBuilder())
            ->withStores(Container::STORES_SAMPLES)
            ->withRows(1)
            ->withColumns(1)
            ->build()
            ->addSample((new SampleBuilder())->build());

        $childB = (new ContainerBuilder())
            ->withStores(Container::STORES_SAMPLES)
            ->withRows(2)
            ->withColumns(2)
            ->build()
            ->addSample((new SampleBuilder())->build());

        $parent
            ->storeContainerInside($childA)
            ->storeContainerInside($childB);

        $this->assertEquals(2, $parent->getNumberOfStoredSamples());
    }

    /**
     * @expectedException \LogicException
     */
    public function testSampleContainersCannotContainOtherContainers()
    {
        $sampleContainer = (new ContainerBuilder())
            ->withStores(Container::STORES_SAMPLES)
            ->withRows(1)
            ->withColumns(1)
            ->build();

        $anotherContainer = clone $sampleContainer;

        $sampleContainer->storeContainerInside($anotherContainer);
    }
}
