<?php

namespace Cscr\SlimsApiBundle\DataFixtures\ORM;

use Cscr\SlimsApiBundle\Entity\Container;
use Cscr\SlimsApiBundle\Entity\ResearchGroup;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadContainerData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    const NUMBER_OF_ROOT_CONTAINERS = 4;
    const NUMBER_OF_SECOND_LEVEL_CONTAINERS = 10;
    const NUMBER_OF_SAMPLE_CONTAINERS = 100;

    /**
     * @var Generator
     */
    private $faker;

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $this->persistRootContainerFixtures();
        $this->persistSecondLevelContainerFixtures();
        $this->persistSampleContainerFixtures();

        $manager->flush();
    }

    private function persistRootContainerFixtures()
    {
        foreach (range(1, self::NUMBER_OF_ROOT_CONTAINERS) as $id) {
            $container = $this->buildContainerThatStoresContainers();
            $this->manager->persist($container);
            $this->addReference(sprintf('root_container_%d', $id), $container);
        }
    }

    private function persistSecondLevelContainerFixtures()
    {
        foreach (range(1, self::NUMBER_OF_SECOND_LEVEL_CONTAINERS) as $id) {
            $colour = rand(0, 1) ? '#a4d11c' : null;
            $container = $this->buildContainerThatStoresContainers()
                ->setColour($colour)
            ;
            /** @var Container $parentContainer */
            $parentContainer = $this->getReference(
                sprintf('root_container_%d', rand(1, self::NUMBER_OF_ROOT_CONTAINERS))
            );
            $this->setReference(sprintf('second_level_container_%d', $id), $container);
            $container->setParent($parentContainer);
            $this->manager->persist($container);
        }
    }

    private function persistSampleContainerFixtures()
    {
        foreach (range(1, self::NUMBER_OF_SAMPLE_CONTAINERS) as $id) {
            $container = $this->buildContainerThatStoresSamples();
            /** @var Container $parentContainer */
            $parentContainer = $this->getReference(
                sprintf('second_level_container_%d', rand(1, self::NUMBER_OF_SECOND_LEVEL_CONTAINERS))
            );
            $container->setParent($parentContainer);
            $this->manager->persist($container);
        }
    }

    private function buildContainerThatStoresSamples()
    {
        $name = sprintf('Container %d', time());
        $rows = rand(1, 10);
        $columns = rand(1, 0);
        $stores = Container::STORES_SAMPLES;
        $container = new Container();
        $container
            ->setName($name)
            ->setRows($rows)
            ->setColumns($columns)
            ->setStores($stores)
            ->setResearchGroup($this->getRandomResearchGroup());

        return $container;
    }

    /**
     * @return Container
     */
    private function buildContainerThatStoresContainers()
    {
        $name = sprintf('Container %d', time());
        $rows = rand(1, 10);
        $columns = rand(1, 0);
        $stores = Container::STORES_CONTAINERS;
        $container = new Container();
        $container
            ->setName($name)
            ->setRows($rows)
            ->setColumns($columns)
            ->setStores($stores)
            ->setResearchGroup($this->getRandomResearchGroup());

        return $container;
    }

    /**
     * @return ResearchGroup
     */
    private function getRandomResearchGroup()
    {
        $groups = range('A', 'F');
        $groupIndex = array_rand($groups);
        $groupReference = sprintf('group_%s', $groups[$groupIndex]);
        $group = $this->getReference($groupReference);

        return $group;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 2;
    }
}
