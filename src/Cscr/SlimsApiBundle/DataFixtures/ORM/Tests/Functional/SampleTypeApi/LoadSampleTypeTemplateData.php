<?php

namespace Cscr\SlimsApiBundle\DataFixtures\ORM\Tests\Functional\SampleTypeApi;

use Cscr\SlimsApiBundle\Tests\Builder\SampleTypeTemplateBuilder;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSampleTypeTemplateData extends AbstractFixture implements OrderedFixtureInterface
{
    const NAME = 'Test';

    public function load(ObjectManager $manager)
    {
        $builder = SampleTypeTemplateBuilder::buildBasicSampleTypeTemplateWithAttributes(self::NAME);
        $template = $builder->build();
        $manager->persist($template);
        $manager->flush();

        $this->setReference('sample-type-template', $template);
    }

    public function getOrder()
    {
        return 2;
    }
}
