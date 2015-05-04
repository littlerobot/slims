<?php

namespace Cscr\SlimsApiBundle\Tests\Functional;

class SampleInstanceTemplateTest extends WebTestCase
{
    const TEMPLATE_NAME = 'Test';

    public function testCreateSampleIntanceTemplateWithNoAttributes()
    {
        $content = $this->getCreateTemplateWithNoAttributesJson(self::TEMPLATE_NAME);

        $this->client->request('POST', '/api/sample-instance-templates', [], [], [], $content);
        $this->assertJsonResponse($this->client->getResponse());

        $template = $this->getTemplateByName(self::TEMPLATE_NAME);
        $this->assertNotNull($template, 'Template was not saved to database');
    }
    public function testCreateSampleInstanceTemplateWithOneOfEachAttribute()
    {
        $content = $this->getCreateTemplateWithOneOfEachAttributeJson(self::TEMPLATE_NAME);
        $this->client->request('POST', '/api/sample-instance-templates', [], [], [], $content);
        $this->assertJsonResponse($this->client->getResponse());

        $template = $this->getTemplateByName(self::TEMPLATE_NAME);
        $this->assertNotNull($template, 'Template was not saved to database');
    }

    public function testUpdateSampleInstanceTemplate()
    {
        $createContent = $this->getCreateTemplateWithOneOfEachAttributeJson(self::TEMPLATE_NAME);
        $updateContent = $this->getUpdateTemplateJson(self::TEMPLATE_NAME);

        $this->client->request('POST', '/api/sample-instance-templates', [], [], [], $createContent);
        $this->assertJsonResponse($this->client->getResponse());

        $template = $this->getTemplateByName(self::TEMPLATE_NAME);
        $this->assertNotNull($template, 'Template was not saved to database');

        $url = sprintf('/api/sample-instance-templates/%d', $template->getId());
        $this->client->request('POST', $url, [], [], [], $updateContent);

        $this->assertJsonResponse($this->client->getResponse());
    }

    public function testListSampleInstanceTemplate()
    {
        $this->client->request('GET', '/api/sample-instance-templates');
        $this->assertJsonResponse($this->client->getResponse());
    }

    /**
     * Returns a minimally-customisable JSON string of a template with all option types.
     *
     * @param string $templateName
     * @return string
     */
    protected function getCreateTemplateWithOneOfEachAttributeJson($templateName)
    {
        return <<<HEREDOC
{
    "name": "${templateName}",
    "store": [
        {
            "order": 1,
            "label": "Store Sex",
            "type": "option",
            "options": [
                "Male",
                "Female"
            ]
        },
        {
            "order": 2,
            "label": "Store Age",
            "type": "brief-text"
        },
        {
            "order": 3,
            "label": "Store Comment",
            "type": "long-text"
        },
        {
            "order": 4,
            "label": "Store document",
            "type": "document"
        },
        {
            "order": 5,
            "label": "Store date",
            "type": "date"
        },
        {
            "order": 6,
            "label": "Store colour",
            "type": "colour"
        },
        {
            "order": 7,
            "label": "Store user",
            "type": "user"
        }
    ],
    "remove": [
        {
            "order": 1,
            "label": "Remove Sex",
            "type": "option",
            "options": [
                "Male",
                "Female"
            ]
        },
        {
            "order": 2,
            "label": "Remove Age",
            "type": "brief-text"
        },
        {
            "order": 3,
            "label": "Remove Comment",
            "type": "long-text"
        }
    ]
}
HEREDOC;

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
     * Returns a minimally-customisable JSON string of a template with no option types.
     *
     * @param string $name
     * @return string
     */
    private function getCreateTemplateWithNoAttributesJson($name)
    {
        return <<<HEREDOC
{
    "name": "${name}"
}
HEREDOC;
    }

    /**
     * Returns a minimally-customisable JSON string of a template with a single attribute in store and one in remove.
     *
     * @param string $name
     * @return string
     */
    private function getUpdateTemplateJson($name)
    {
        return <<<HEREDOC
{
    "name": "${name}",
    "store": [
        {
            "order": 1,
            "label": "Store user",
            "type": "user"
        }
    ],
    "remove": [
         {
            "order": 1,
            "label": "Remove Age",
            "type": "brief-text"
        }
    ]
}
HEREDOC;
    }
}
