<?php

namespace Cscr\SlimsApiBundle\Response;

use Cscr\SlimsApiBundle\Entity\Sample;
use JMS\Serializer\Annotation as JMS;

class FilteredSamplesResponse
{
    /**
     * @var bool
     */
    protected $success = false;

    /**
     * @var mixed
     *
     * @JMS\SerializedName("results")
     */
    protected $data;

    public function __construct(array $results)
    {
        if (!empty($results)) {
            $this->success = true;

            $this->map($results);
        }
    }

    private function map(array $results)
    {
        /** @var Sample $sample */
        foreach ($results as $sample) {
            $container = $sample->getContainer();

            $sampleType = $sample->getType();
            $sampleInstanceTemplate = $sample->getTemplate();
            $result = [
                'container' => $container->getId(),
                'container_name' => $sample->getHierarchy(),
                'row' => $sample->getRow(),
                'column' => $sample->getColumn(),
                'colour' => $sample->getColour(),
                'type' => $sampleType->getId(),
                'type_name' => $sampleType->getName(),
                'instance_template' => $sampleInstanceTemplate->getId(),
                'instance_template_name' => $sampleInstanceTemplate->getName(),
                'instance_attributes' => [],
                'type_attributes' => [],
            ];

            foreach ($sample->getAttributes() as $instanceAttribute) {
                $sampleInstanceTemplateStoredAttribute = $instanceAttribute->getTemplate();
                $result['instance_attributes'][] = [
                    'id' => $instanceAttribute->getId(),
                    'label' => $sampleInstanceTemplateStoredAttribute->getLabel(),
                    'type' => $sampleInstanceTemplateStoredAttribute->getType(),
                    'value' => $instanceAttribute->getNonBinaryValue(),
                    'url' => $instanceAttribute->getUrl(),
                ];
            }

            foreach ($sampleType->getAttributes() as $typeAttribute) {
                $sampleTypeTemplateAttribute = $typeAttribute->getTemplate();
                $result['type_attributes'][] = [
                    'id' => $typeAttribute->getId(),
                    'label' => $sampleTypeTemplateAttribute->getLabel(),
                    'type' => $sampleTypeTemplateAttribute->getType(),
                    'value' => $typeAttribute->getNonBinaryValue(),
                    'url' => $typeAttribute->getUrl(),
                ];
            }

            $this->data[] = $result;
        }
    }
}
