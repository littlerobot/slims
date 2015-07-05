<?php

namespace Cscr\SlimsApiBundle\Tests\Renderer;

use Cscr\SlimsApiBundle\Entity\SampleTypeTemplateAttribute;

class SampleTypeTemplateAttributeRender
{
    public static function renderAsArray(SampleTypeTemplateAttribute $attribute)
    {
        $array = [
            'label' => $attribute->getLabel(),
            'order' => $attribute->getOrder(),
            'type' => $attribute->getType(),
        ];

        if ($attribute->getOptions()) {
            $array['options'] = $attribute->getOptions();
        }

        return $array;
    }
}
