<?php

namespace Cscr\SlimsApiBundle\Tests\Functional;
use Cscr\SlimsApiBundle\Tests\Builder\SampleInstanceTemplateAttributeBuilder;
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
        $builder = $this->buildBuilderWithAllAttributeTypes(self::TEMPLATE_NAME);
        $content = SampleInstanceTemplateRenderer::renderAsJson($builder->build());

        $this->client->request('POST', '/api/sample-instance-templates', [], [], [], $content);
        $this->assertJsonResponse($this->client->getResponse());

        $template = $this->getTemplateByName(self::TEMPLATE_NAME);
        $this->assertNotNull($template, 'Template was not saved to database');
    }

    public function testUpdateSampleInstanceTemplate()
    {
        $createName = self::TEMPLATE_NAME . '-create';
        $createBuilder = $this->buildBuilderWithAllAttributeTypes($createName);
        $createContent = SampleInstanceTemplateRenderer::renderAsJson($createBuilder->build());
        $updateName = self::TEMPLATE_NAME . '-update';
        $updateBuilder = $this->buildBuilderWithAllAttributeTypes($updateName)->shuffleAttributes();
        $updateContent = SampleInstanceTemplateRenderer::renderAsJson($updateBuilder->build());

        $this->client->request('POST', '/api/sample-instance-templates', [], [], [], $createContent);
        $this->assertJsonResponse($this->client->getResponse());

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

    /**
     * @param $name
     * @return SampleInstanceTemplateBuilder
     */
    private function buildBuilderWithAllAttributeTypes($name)
    {
        return (new SampleInstanceTemplateBuilder())
            ->withName($name)
            ->withStoredAttribute(SampleInstanceTemplateAttributeBuilder::aBriefTextAttribute()->withOrder(1))
            ->withStoredAttribute(SampleInstanceTemplateAttributeBuilder::aLongTextAttribute()->withOrder(2))
            ->withStoredAttribute(SampleInstanceTemplateAttributeBuilder::anOptionAttribute()->withOrder(3))
            ->withStoredAttribute(SampleInstanceTemplateAttributeBuilder::aDocumentAttribute()->withOrder(4))
            ->withStoredAttribute(SampleInstanceTemplateAttributeBuilder::aDateAttribute()->withOrder(5))
            ->withStoredAttribute(SampleInstanceTemplateAttributeBuilder::aColourAttribute()->withOrder(6))
            ->withStoredAttribute(SampleInstanceTemplateAttributeBuilder::aUserAttribute()->withOrder(7))

            ->withRemovedAttribute(SampleInstanceTemplateAttributeBuilder::aBriefTextAttribute()->withOrder(1))
            ->withRemovedAttribute(SampleInstanceTemplateAttributeBuilder::aLongTextAttribute()->withOrder(2))
            ->withRemovedAttribute(SampleInstanceTemplateAttributeBuilder::anOptionAttribute()->withOrder(3))
            ->withRemovedAttribute(SampleInstanceTemplateAttributeBuilder::aDocumentAttribute()->withOrder(4))
            ->withRemovedAttribute(SampleInstanceTemplateAttributeBuilder::aDateAttribute()->withOrder(5))
            ->withRemovedAttribute(SampleInstanceTemplateAttributeBuilder::aColourAttribute()->withOrder(6))
            ->withRemovedAttribute(SampleInstanceTemplateAttributeBuilder::aUserAttribute()->withOrder(7))
        ;
    }
}
