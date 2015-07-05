<?php

namespace Cscr\SlimsApiBundle\DataFixtures\ORM\Tests\Functional\SampleTypeApi;

use Cscr\SlimsApiBundle\Entity\SampleTypeTemplate;
use Cscr\SlimsApiBundle\Tests\Builder\SampleTypeBuilder;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSampleTypeData extends AbstractFixture implements OrderedFixtureInterface
{
    const NAME = 'Test';

    public function load(ObjectManager $manager)
    {
        /** @var SampleTypeTemplate $sampleTypeTemplate */
        $sampleTypeTemplate = $this->getReference('sample-type-template');
        $builder = SampleTypeBuilder::buildSampleTypeBuilderWithAttributes(
            $sampleTypeTemplate,
            self::NAME
        );
        $type = $builder->build();
        $manager->persist($type);

        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}
