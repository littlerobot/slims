<?php

namespace Cscr\SlimsApiBundle\Tests\Functional;

use Cscr\SlimsApiBundle\Entity\SampleTypeTemplate;
use Cscr\SlimsApiBundle\Tests\Builder\SampleTypeTemplateAttributeBuilder;
use Cscr\SlimsApiBundle\Tests\Builder\SampleTypeTemplateBuilder;
use Cscr\SlimsApiBundle\Tests\Renderer\SampleTypeTemplateRenderer;

/**
 * @group functional
 */
class SampleTypeTemplateApiTest extends WebTestCase
{
    public function testCreateTemplateWithNoAttributes()
    {
        $name = 'ABCDEFGH1234567';
        $builder = new SampleTypeTemplateBuilder();
        $builder->withName($name);
        $content = SampleTypeTemplateRenderer::renderAsJson($builder->build());

        $this->client->request('POST', '/api/sample-type-templates', [], [], [], $content);
        $this->assertJsonResponse($this->client->getResponse());

        $template = $this->getTemplateByName($name);
        $this->assertNotNull($template, 'Template was not saved to database');
    }

    public function testCreateTemplateWithOneOfEachAttribute()
    {
        $name = 'ABCDEFGH1234567';
        $builder = $this->buildBuilderWithAllAttributeTypes($name);
        $content = SampleTypeTemplateRenderer::renderAsJson($builder->build());

        $this->client->request('POST', '/api/sample-type-templates', [], [], [], $content);
        $this->assertJsonResponse($this->client->getResponse());

        $template = $this->getTemplateByName($name);
        $this->assertNotNull($template, 'Template was not saved to database');

        $this->assertCount(7, $template->getAttributes());
    }

    public function testReorderAttributesInExistingTemplate()
    {
        $name = 'ABCDEFGH1234567';
        $builder = $this->buildBuilderWithAllAttributeTypes($name);
        $content = SampleTypeTemplateRenderer::renderAsJson($builder->build());

        $this->client->request('POST', '/api/sample-type-templates', [], [], [], $content);
        $this->assertJsonResponse($this->client->getResponse());

        $template = $this->getTemplateByName($name);
        $this->assertNotNull($template, 'Template was not saved to database');

        $url = sprintf('/api/sample-type-templates/%d', $template->getId());
        $builder->switchOrderOfFirstAndLastAttributes();
        $content = SampleTypeTemplateRenderer::renderAsJson($builder->build());

        $this->client->request('POST', $url, [], [], [], $content);
        $this->assertJsonResponse($this->client->getResponse());

        $this->clearDoctrineCache();
        $template = $this->getTemplateByName($name);
        $attributes = $template->getAttributes();

        $this->assertLessThan(
            $attributes->first()->getOrder(),
            $attributes->last()->getOrder(),
            'Attributes were not re-ordered'
        );
    }

    public function testAddOptionAttributeWithoutAnyOptionsFails()
    {
        $builder = (new SampleTypeTemplateBuilder())
            ->withAttribute(SampleTypeTemplateAttributeBuilder::anOptionAttribute()->withoutOptions());
        $content = SampleTypeTemplateRenderer::renderAsJson($builder->build());

        $this->client->request('POST', '/api/sample-type-templates', [], [], [], $content);
        $this->assertJsonResponse($this->client->getResponse(), 400);
    }

    public function testAddBriefTextAttributeWithOptionsFails()
    {
        $builder = (new SampleTypeTemplateBuilder())
            ->withAttribute(SampleTypeTemplateAttributeBuilder::aBriefTextAttribute()->withOptions(['foo', 'bar']));
        $content = SampleTypeTemplateRenderer::renderAsJson($builder->build());

        $this->client->request('POST', '/api/sample-type-templates', [], [], [], $content);
        $this->assertJsonResponse($this->client->getResponse(), 400);
    }

    public function testListSampleTypeTemplates()
    {
        $this->client->request('GET', '/api/sample-type-templates');
        $this->assertJsonResponse($this->client->getResponse());
    }

    /**
     * @param string $name
     *
     * @return SampleTypeTemplate
     */
    private function getTemplateByName($name)
    {
        return $this
            ->getContainer()
            ->get('doctrine')
            ->getRepository('CscrSlimsApiBundle:SampleTypeTemplate')
            ->findOneBy([
                'name' => $name,
            ]);
    }

    /**
     * @param $name
     *
     * @return SampleTypeTemplateBuilder
     */
    private function buildBuilderWithAllAttributeTypes($name)
    {
        return (new SampleTypeTemplateBuilder())
            ->withName($name)
            ->withAttribute(SampleTypeTemplateAttributeBuilder::aBriefTextAttribute()->withOrder(1))
            ->withAttribute(SampleTypeTemplateAttributeBuilder::aLongTextAttribute()->withOrder(2))
            ->withAttribute(SampleTypeTemplateAttributeBuilder::anOptionAttribute()->withOrder(3))
            ->withAttribute(SampleTypeTemplateAttributeBuilder::aDocumentAttribute()->withOrder(4))
            ->withAttribute(SampleTypeTemplateAttributeBuilder::aDateAttribute()->withOrder(5))
            ->withAttribute(SampleTypeTemplateAttributeBuilder::aColourAttribute()->withOrder(6))
            ->withAttribute(SampleTypeTemplateAttributeBuilder::aUserAttribute()->withOrder(7));
    }
}
