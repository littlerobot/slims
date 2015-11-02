<?php

namespace Cscr\SlimsApiBundle\Tests\Functional;

use Cscr\SlimsApiBundle\Tests\Builder\SampleInstanceTemplateBuilder;
use Cscr\SlimsApiBundle\Tests\Renderer\SampleInstanceTemplateRenderer;

/**
 * @group functional
 */
class SampleInstanceTemplateApiTest extends WebTestCase
{
    const TEMPLATE_NAME = 'Test';

    public function testCreateSampleInstanceTemplateWithNoAttributes()
    {
        $builder = (new SampleInstanceTemplateBuilder())
            ->withName(self::TEMPLATE_NAME);
        $content = SampleInstanceTemplateRenderer::renderAsJson($builder->build());

        $this->client->request('POST', '/api/sample-instance-templates', [], [], [], $content);
        $this->assertJsonResponse($this->client->getResponse());

        $template = $this->getTemplateByName(self::TEMPLATE_NAME);
        $this->assertNotNull($template, 'Template was not saved to database');
    }

    public function testCreateSampleInstanceTemplateWithOneOfEachAttribute()
    {
        $builder = SampleInstanceTemplateBuilder::buildBuilderWithAllAttributeTypes(self::TEMPLATE_NAME);
        $content = SampleInstanceTemplateRenderer::renderAsJson($builder->build());

        $this->client->request('POST', '/api/sample-instance-templates', [], [], [], $content);
        $this->assertJsonResponse($this->client->getResponse());

        $template = $this->getTemplateByName(self::TEMPLATE_NAME);
        $this->assertNotNull($template, 'Template was not saved to database');
    }

    public function testUpdateSampleInstanceTemplate()
    {
        $this->loadFixtures([
            'Cscr\SlimsApiBundle\DataFixtures\ORM\Tests\Functional\SampleInstanceTemplateApi\LoadSampleInstanceTemplateData',
        ]);

        $createName = self::TEMPLATE_NAME.'-create';
        $updateName = self::TEMPLATE_NAME.'-update';
        $updateBuilder = SampleInstanceTemplateBuilder::buildBuilderWithAllAttributeTypes($updateName)
            ->shuffleAttributes();
        $updateContent = SampleInstanceTemplateRenderer::renderAsJson($updateBuilder->build());

        $template = $this->getTemplateByName($createName);
        $url = sprintf('/api/sample-instance-templates/%d', $template->getId());
        $this->client->request('POST', $url, [], [], [], $updateContent);

        $this->assertJsonResponse($this->client->getResponse());
        $this->assertNotNull($this->getTemplateByName($updateName), 'Update was not saved to database');
    }

    public function testListSampleInstanceTemplate()
    {
        $this->client->request('GET', '/api/sample-instance-templates');
        $this->assertJsonResponse($this->client->getResponse());
    }

    /**
     * @param string $name
     *
     * @return \Cscr\SlimsApiBundle\Entity\SampleInstanceTemplate|null
     */
    private function getTemplateByName($name)
    {
        return $this
            ->getContainer()
            ->get('doctrine')
            ->getRepository('CscrSlimsApiBundle:SampleInstanceTemplate')
            ->findOneBy([
                'name' => $name,
            ]);
    }
}
