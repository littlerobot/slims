<?php

namespace Cscr\SlimsApiBundle\DataFixtures\ORM\Tests\Functional\SampleTypeApi;

use Cscr\SlimsApiBundle\Tests\Builder\SampleInstanceTemplateBuilder;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSampleInstanceTemplateData extends AbstractFixture implements OrderedFixtureInterface
{
    const TEMPLATE_NAME = 'Test';

    public function load(ObjectManager $manager)
    {
        $builder = SampleInstanceTemplateBuilder::buildBuilderWithAllAttributeTypes(self::TEMPLATE_NAME);
        $template = $builder->build();
        $manager->persist($template);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
