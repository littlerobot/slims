<?php

namespace Cscr\SlimsApiBundle\DataFixtures\ORM\Tests\Functional\SampleInstanceTemplateApi;

use Cscr\SlimsApiBundle\Tests\Builder\SampleInstanceTemplateBuilder;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSampleInstanceTemplateData extends AbstractFixture implements FixtureInterface
{
    const TEMPLATE_NAME = 'Test-create';

    public function load(ObjectManager $manager)
    {
        $builder = SampleInstanceTemplateBuilder::buildBuilderWithAllAttributeTypes(self::TEMPLATE_NAME);
        $template = $builder->build();
        $manager->persist($template);

        $manager->flush();
    }
}
