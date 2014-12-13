<?php

namespace Cscr\SlimsApiBundle\DataFixtures\ORM;

use Cscr\SlimsApiBundle\Entity\ResearchGroup;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadResearchGroupData extends AbstractFixture implements FixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach (range('A', 'F') as $letter) {
            $group = new ResearchGroup();
            $group->setName(sprintf('Research group %s', $letter));
            $manager->persist($group);
        }
        $manager->flush();
    }
}
