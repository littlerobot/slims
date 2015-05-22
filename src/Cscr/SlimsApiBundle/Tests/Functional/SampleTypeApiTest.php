<?php

namespace Cscr\SlimsApiBundle\Tests\Functional;

use Cscr\SlimsApiBundle\Entity\SampleType;
use Cscr\SlimsApiBundle\Entity\SampleTypeTemplate;
use Cscr\SlimsApiBundle\Tests\Builder\SampleTypeBuilder;
use Cscr\SlimsApiBundle\Tests\Renderer\SampleTypeRenderer;

/**
 * @group functional
 */
class SampleTypeApiTest extends WebTestCase
{
    const NAME = 'Test';

    public function testCreateSampleType()
    {
        $this->loadFixtures([
            'Cscr\SlimsApiBundle\DataFixtures\ORM\Tests\Functional\SampleTypeApi\LoadSampleInstanceTemplateData',
            'Cscr\SlimsApiBundle\DataFixtures\ORM\Tests\Functional\SampleTypeApi\LoadSampleTypeTemplateData',
        ]);

        $template = $this->findSampleTypeTemplate(self::NAME);
        $builder = SampleTypeBuilder::buildSampleTypeBuilderWithAttributes($template, self::NAME);

        $content = SampleTypeRenderer::renderAsJson($builder->build());
        $this->client->request('POST', '/api/sample-types', [], [], [], $content);
        $this->assertJsonResponse($this->client->getResponse());

        $type = $this->findSampleTypeByName(self::NAME);
        $this->assertNotNull($type, 'Sample type was not saved');
    }

    public function testUpdateSampleType()
    {
        $this->loadFixtures([
            'Cscr\SlimsApiBundle\DataFixtures\ORM\Tests\Functional\SampleTypeApi\LoadSampleInstanceTemplateData',
            'Cscr\SlimsApiBundle\DataFixtures\ORM\Tests\Functional\SampleTypeApi\LoadSampleTypeData',
            'Cscr\SlimsApiBundle\DataFixtures\ORM\Tests\Functional\SampleTypeApi\LoadSampleTypeTemplateData',
        ]);

        $template = $this->findSampleTypeTemplate(self::NAME);
        $typeBuilder = SampleTypeBuilder::buildSampleTypeBuilderWithAttributes($template, self::NAME);
        $typeBuilder->getAttributes()[0]->withValue('Updated');

        $content = SampleTypeRenderer::renderAsJson($typeBuilder->build());
        $type = $this->findSampleTypeByName(self::NAME);

        $this->client->request('POST', sprintf('/api/sample-types/%d', $type->getId()), [], [], [], $content);
        $this->assertJsonResponse($this->client->getResponse());

        // Reset manager to load data from database next query.
        $this->getContainer()->get('doctrine')->resetManager();
        $type = $this->findSampleTypeByName(self::NAME);

        $this->assertEquals('Updated', $type->getAttributes()[0]->getValue());
    }

    public function testListSampleTypes()
    {
        $this->client->request('GET', '/api/sample-types');
        $this->assertJsonResponse($this->client->getResponse());
    }

    /**
     * @param string $name
     * @return SampleType
     */
    private function findSampleTypeByName($name)
    {
        return $this
            ->getContainer()
            ->get('doctrine')
            ->getRepository('CscrSlimsApiBundle:SampleType')
            ->findOneBy([
                'name' => $name,
            ]);
    }

    /**
     * @param string $name
     * @return SampleTypeTemplate
     */
    private function findSampleTypeTemplate($name)
    {
        return $this
            ->getContainer()
            ->get('doctrine')
            ->getRepository('CscrSlimsApiBundle:SampleTypeTemplate')
            ->findOneBy([
                'name' => $name,
            ]);
    }
}
