<?php

namespace Cscr\SlimsApiBundle\Tests\Functional;

use Cscr\SlimsApiBundle\Entity\SampleType;
use Cscr\SlimsApiBundle\Entity\SampleTypeTemplate;
use Cscr\SlimsApiBundle\Entity\SampleTypeTemplateAttribute;
use Cscr\SlimsApiBundle\Tests\Builder\SampleTypeAttributeBuilder;
use Cscr\SlimsApiBundle\Tests\Builder\SampleTypeBuilder;
use Cscr\SlimsApiBundle\Tests\Builder\SampleTypeTemplateAttributeBuilder;
use Cscr\SlimsApiBundle\Tests\Builder\SampleTypeTemplateBuilder;
use Cscr\SlimsApiBundle\Tests\Renderer\SampleTypeRenderer;

/**
 * @group functional
 */
class SampleTypeApiTest extends WebTestCase
{
    const NAME = 'Test';

    public function testCreateSampleType()
    {
        $templateBuilder = $this->createBasicSampleTypeTemplateWithAttributes(self::NAME);
        $builder = $this->buildSampleTypeBuilderWithAttributes($templateBuilder);

        $content = SampleTypeRenderer::renderAsJson($builder->build());
        $this->client->request('POST', '/api/sample-types', [], [], [], $content);
        $this->assertJsonResponse($this->client->getResponse());

        $type = $this->getSampleTypeByName(self::NAME);
        $this->assertNotNull($type, 'Sample type was not saved');
    }

    public function testUpdateSampleType()
    {
        $templateBuilder = $this->createBasicSampleTypeTemplateWithAttributes(self::NAME);
        $createBuilder = $this->buildSampleTypeBuilderWithAttributes($templateBuilder);
        $updateBuilder = clone $createBuilder;
        $updateBuilder->getAttributes()[0]->withValue('Updated');

        $createContent = SampleTypeRenderer::renderAsJson($createBuilder->build());
        $updateContent = SampleTypeRenderer::renderAsJson($updateBuilder->build());

        $this->client->request('POST', '/api/sample-types', [], [], [], $createContent);
        $this->assertJsonResponse($this->client->getResponse());

        $type = $this->getSampleTypeByName(self::NAME);
        $this->assertNotNull($type, 'Sample type was not saved');

        $this->client->request('POST', sprintf('/api/sample-types/%d', $type->getId()), [], [], [], $updateContent);
        $this->assertJsonResponse($this->client->getResponse());
    }

    public function testListSampleTypes()
    {
        $this->client->request('GET', '/api/sample-types');
        $this->assertJsonResponse($this->client->getResponse());
    }

    /**
     * @param string $name
     * @return SampleTypeTemplate
     */
    protected function createBasicSampleTypeTemplateWithAttributes($name)
    {
        $template = (new SampleTypeTemplateBuilder())
            ->withName($name)
            ->withAttribute(SampleTypeTemplateAttributeBuilder::aBriefTextAttribute())
            ->withAttribute(SampleTypeTemplateAttributeBuilder::aLongTextAttribute())
            ->build();

        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($template);
        $em->flush();

        return $template;
    }

    private function getSampleTypeByName($name)
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
     * @param SampleTypeTemplate $sampleTypeTemplate
     * @return SampleTypeBuilder
     */
    private function buildSampleTypeBuilderWithAttributes(SampleTypeTemplate $sampleTypeTemplate)
    {
        $builder = (new SampleTypeBuilder())
            ->withName(self::NAME)
            ->withTemplate($sampleTypeTemplate)
        ;

        foreach ($sampleTypeTemplate->getAttributes() as $attributeTemplate) {
            switch ($attributeTemplate->getType()) {
                case SampleTypeTemplateAttribute::TYPE_BRIEF_TEXT:
                default:
                    $attributeBuilder = SampleTypeAttributeBuilder::aBriefTextAttribute($attributeTemplate);
                    break;
                case SampleTypeTemplateAttribute::TYPE_LONG_TEXT:
                    $attributeBuilder = SampleTypeAttributeBuilder::aLongTextAttribute($attributeTemplate);
                    break;
            }

            $builder->withAttribute($attributeBuilder);
        }

        return $builder;
    }
}
