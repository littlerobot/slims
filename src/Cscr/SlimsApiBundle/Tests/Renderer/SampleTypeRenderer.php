<?php

namespace Cscr\SlimsApiBundle\Tests\Renderer;

use Cscr\SlimsApiBundle\Entity\SampleType;

class SampleTypeRenderer
{
    public static function renderAsJson(SampleType $type)
    {
        return json_encode(self::renderAsArray($type));
    }

    /**
     * @param SampleType $type
     * @return array
     */
    private static function renderAsArray(SampleType $type)
    {
        $array = [
            'name' => $type->getName(),
            'sample_type_template' => $type->getTemplate()->getId(),
            'attributes' => [],
        ];

        foreach ($type->getAttributes() as $attribute) {
            $array['attributes'][] = [
                'id' => $attribute->getTemplate()->getId(),
                'value' => $attribute->getValue(),
            ];
        }

        return $array;
    }
}
