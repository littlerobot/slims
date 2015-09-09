<?php

namespace Cscr\SlimsUserBundle\DataFixtures\ORM;

use Cscr\SlimsApiBundle\Entity\ResearchGroup;
use Cscr\SlimsUserBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class LoadUserData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * @var Generator
     */
    private $faker;

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->faker = Factory::create();
        $this->manager = $manager;

        foreach (range(1, 10) as $counter) {
            $group = $this->getRandomResearchGroup();
            $user = new User();
            $user
                ->setUsername(sprintf('test%04d', $counter))
                ->setName($this->faker->name)
                ->setActive(true)
                ->setResearchGroup($group)
            ;
            $this->manager->persist($user);
        }

        $this->manager->flush();
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
        return 3;
    }
}
