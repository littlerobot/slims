<?php

namespace Cscr\SlimsApiBundle\DataFixtures\ORM\Tests\Functional\ContainerApi;

use Cscr\SlimsApiBundle\Tests\Builder\ContainerBuilder;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadContainerData extends AbstractFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Persist a simple container with no configuration.
        $builder = new ContainerBuilder();
        $container = $builder->build();
        $manager->persist($container);
        $manager->persist($container->getResearchGroup());

        $manager->flush();
    }
}
