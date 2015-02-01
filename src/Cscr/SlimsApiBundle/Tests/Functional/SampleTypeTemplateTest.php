<?php

namespace Cscr\SlimsApiBundle\Tests\Functional;

use Cscr\SlimsApiBundle\Entity\SampleTypeTemplate;
use Cscr\SlimsApiBundle\Tests\Builder\SampleTypeAttributeBuilder;
use Cscr\SlimsApiBundle\Tests\Builder\SampleTypeTemplateBuilder;

class SampleTypeTemplateTest extends WebTestCase
{
    public function testCreateTemplateWithNoAttributes()
    {
        $name = 'ABCDEFGH1234567';
        $builder = new SampleTypeTemplateBuilder();
        $builder->withName($name);

        $this->client->request('POST', '/api/sample-type-templates', [], [], [], $builder->buildAsJson());
        $this->assertJsonResponse($this->client->getResponse());

        $template = $this->getTemplateByName($name);
        $this->assertNotNull($template, 'Template was not saved to database');
    }

    public function testCreateTemplateWithOneOfEachAttribute()
    {
        $name = 'ABCDEFGH1234567';
        $builder = $this->buildBuilderWithAllAttributeTypes($name);

        $this->client->request('POST', '/api/sample-type-templates', [], [], [], $builder->buildAsJson());
        $this->assertJsonResponse($this->client->getResponse());

        $template = $this->getTemplateByName($name);
        $this->assertNotNull($template, 'Template was not saved to database');

        $this->assertCount(7, $template->getAttributes());
    }

    public function testReorderAttributesInExistingTemplate()
    {
        $name = 'ABCDEFGH1234567';
        $builder = $this->buildBuilderWithAllAttributeTypes($name);

        $this->client->request('POST', '/api/sample-type-templates', [], [], [], $builder->buildAsJson());
        $this->assertJsonResponse($this->client->getResponse());

        $template = $this->getTemplateByName($name);
        $this->assertNotNull($template, 'Template was not saved to database');

        $url = sprintf('/api/sample-type-template/%d', $template->getId());
        $builder->switchOrderOfFirstAndLastAttributes();
        $this->client->request('POST', $url, [], [], [], $builder->buildAsJson());

        $this->clearDoctrineCache();
        $template = $this->getTemplateByName($name);
        $attributes = $template->getAttributes();

        $this->assertLessThan(
            $attributes->last()->getOrder(),
            $attributes->first()->getOrder(),
            'Attributes were not re-ordered'
        );
    }

    public function testAddOptionAttributeWithoutAnyOptionsFails()
    {
        $builder = (new SampleTypeTemplateBuilder())
            ->withAttribute(SampleTypeAttributeBuilder::anOptionAttribute()->withoutOptions());

        $this->client->request('POST', '/api/sample-type-templates', [], [], [], $builder->buildAsJson());
        $this->assertJsonResponse($this->client->getResponse(), 400);
    }

    public function testAddBriefTextAttributeWithOptionsFails()
    {
        $builder = (new SampleTypeTemplateBuilder())
            ->withAttribute(SampleTypeAttributeBuilder::aBriefTextAttribute()->withOptions(['foo', 'bar']));

        $this->client->request('POST', '/api/sample-type-templates', [], [], [], $builder->buildAsJson());
        $this->assertJsonResponse($this->client->getResponse(), 400);
    }

    public function testListSampleTypeTemplates()
    {
        $this->client->request('GET', '/api/sample-type-templates');
        $this->assertJsonResponse($this->client->getResponse());
    }

    /**
     * @param string $name
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
            ])
        ;
    }

    /**
     * @param $name
     * @return SampleTypeTemplateBuilder
     */
    private function buildBuilderWithAllAttributeTypes($name)
    {
        return (new SampleTypeTemplateBuilder())
            ->withName($name)
            ->withAttribute(SampleTypeAttributeBuilder::aBriefTextAttribute()->withOrder(1))
            ->withAttribute(SampleTypeAttributeBuilder::aLongTextAttribute()->withOrder(2))
            ->withAttribute(SampleTypeAttributeBuilder::anOptionAttribute()->withOrder(3))
            ->withAttribute(SampleTypeAttributeBuilder::aDocumentAttribute()->withOrder(4))
            ->withAttribute(SampleTypeAttributeBuilder::aDateAttribute()->withOrder(5))
            ->withAttribute(SampleTypeAttributeBuilder::aColourAttribute()->withOrder(6))
            ->withAttribute(SampleTypeAttributeBuilder::aUserAttribute()->withOrder(7))
        ;
    }
}
