<?php

namespace Cscr\SlimsApiBundle\Tests\Functional;

use Cscr\SlimsApiBundle\Tests\Builder\SampleTypeTemplateAttributeBuilder;
use Cscr\SlimsApiBundle\Tests\Builder\SampleTypeTemplateBuilder;

/**
 * @group functional
 */
class SampleTypeTest extends WebTestCase
{
    const NAME = 'Test';

    public function testCreateSampleType()
    {
        $this->createBasicSampleTypeTemplate(self::NAME);
        $content = $this->getCreateSampleTypeJson(self::NAME);
        $this->client->request('POST', '/api/sample-types', [], [], [], $content);
        $this->assertJsonResponse($this->client->getResponse());

        $type = $this->getSampleTypeByName(self::NAME);
        $this->assertNotNull($type, 'Sample type was not saved');
    }

    public function testUpdateSampleType()
    {
        $this->createBasicSampleTypeTemplate(self::NAME);

        $createContent = $this->getCreateSampleTypeJson(self::NAME);
        $updateContent = $this->getUpdateSampleTypeJson(self::NAME);

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

    protected function getCreateSampleTypeJson($name)
    {
        return <<<HEREDOC
{
  "name": "${name}",
  "sample_type_template": 1,
  "attributes": [
    {
      "template": 1,
      "value": "Trypsin"
    }
  ]
}
HEREDOC;
    }

    protected function createBasicSampleTypeTemplate($name)
    {
        $template = (new SampleTypeTemplateBuilder())
            ->withName($name)
            ->withAttribute(SampleTypeTemplateAttributeBuilder::aBriefTextAttribute())
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

    private function getUpdateSampleTypeJson($name)
    {
        return <<<HEREDOC
{
  "name": "${name}",
  "sample_type_template": 1,
  "attributes": [
    {
      "template": 1,
      "value": "Trypsin updated"
    }
  ]
}
HEREDOC;
    }
}
