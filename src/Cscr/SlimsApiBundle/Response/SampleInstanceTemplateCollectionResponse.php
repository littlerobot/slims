<?php

namespace Cscr\SlimsApiBundle\Response;

use Cscr\SlimsApiBundle\Entity\AbstractSampleInstanceTemplateAttribute;
use Cscr\SlimsApiBundle\Entity\SampleInstanceTemplate;
use Doctrine\Common\Collections\Selectable;
use JMS\Serializer\Annotation as JMS;

class SampleInstanceTemplateCollectionResponse extends ExtJsResponse
{
    /**
     * @var
     *
     * @JMS\SerializedName("sample_instance_templates")
     */
    protected $data;

    /**
     * @param SampleInstanceTemplate[] $templates
     */
    public function __construct(array $templates)
    {
        $this->success = !empty($templates);

        $this->map($templates);
    }

    /**
     * @param SampleInstanceTemplate[] $templates
     */
    private function map(array $templates)
    {
        foreach ($templates as $template) {
            $this->data[] = [
                'id' => $template->getId(),
                'name' => $template->getName(),
                'editable' => $template->isEditable(),
                'store' => $this->mapAttributes($template->getStoredAttributes()),
                'remove' => $this->mapAttributes($template->getRemovedAttributes()),
            ];
        }
    }

    /**
     * @param Selectable|AbstractSampleInstanceTemplateAttribute[] $attributes
     *
     * @return array
     */
    private function mapAttributes(Selectable $attributes)
    {
        $data = [];

        foreach ($attributes as $attribute) {
            $data[] = [
                'id' => $attribute->getId(),
                'order' => $attribute->getOrder(),
                'label' => $attribute->getLabel(),
                'type' => $attribute->getType(),
            ];
        }

        return $data;
    }
}
