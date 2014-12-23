<?php

namespace Cscr\SlimsApiBundle\DataFixtures\ORM;

use Cscr\SlimsApiBundle\Entity\Container;
use Cscr\SlimsApiBundle\Entity\ResearchGroup;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

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
        $this->faker = Factory::create();
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
            $container = $this->buildContainerThatStoresContainers()
                ->setColour($this->faker->optional(0.5)->hexcolor)
            ;
            /** @var Container $parentContainer */
            $parentContainer = $this->getReference(
                sprintf('root_container_%d', rand(1, self::NUMBER_OF_ROOT_CONTAINERS))
            );
            $this->setReference(sprintf('second_level_container_%d', $id), $container);
            $parentContainer->storeContainerInside($container);
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
            $parentContainer->storeContainerInside($container);
            $this->manager->persist($container);
        }
    }

    private function buildContainerThatStoresSamples()
    {
        $name = $this->faker->sentence(2);
        $rows = $this->faker->numberBetween(1, 10);
        $columns = $this->faker->numberBetween(1, 10);
        $stores = Container::STORES_SAMPLES;
        $container = new Container();
        $container
            ->setName($name)
            ->setRows($rows)
            ->setColumns($columns)
            ->setStores($stores)
            ->setResearchGroup($this->getRandomResearchGroup())
            ->setColour($this->faker->optional(0.75)->hexcolor)
            ->setComment($this->faker->text(50));

        return $container;
    }

    /**
     * @return Container
     */
    private function buildContainerThatStoresContainers()
    {
        $name = $this->faker->sentence(2);
        $rows = $this->faker->numberBetween(1, 10);
        $columns = $this->faker->numberBetween(1, 10);
        $stores = Container::STORES_CONTAINERS;
        $container = new Container();
        $container
            ->setName($name)
            ->setRows($rows)
            ->setColumns($columns)
            ->setStores($stores)
            ->setResearchGroup($this->getRandomResearchGroup())
            ->setColour($this->faker->optional(0.25)->hexcolor)
            ->setComment($this->faker->text(50));

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
