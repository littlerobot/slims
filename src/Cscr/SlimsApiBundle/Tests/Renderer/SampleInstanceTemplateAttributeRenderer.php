<?php

namespace Cscr\SlimsApiBundle\Tests\Renderer;

use Cscr\SlimsApiBundle\Entity\AbstractSampleInstanceTemplateAttribute;

class SampleInstanceTemplateAttributeRenderer
{
    public static function renderAsArray(AbstractSampleInstanceTemplateAttribute $attribute)
    {
        return [
            'order' => $attribute->getOrder(),
            'label' => $attribute->getLabel(),
            'type' => $attribute->getType(),
        ];
    }

    public static function renderAsJson(AbstractSampleInstanceTemplateAttribute $attribute)
    {
        return json_encode(self::renderAsArray($attribute));
    }
}
