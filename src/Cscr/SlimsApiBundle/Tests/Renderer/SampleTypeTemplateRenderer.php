<?php

namespace Cscr\SlimsApiBundle\Tests\Renderer;

use Cscr\SlimsApiBundle\Entity\SampleTypeTemplate;

class SampleTypeTemplateRenderer
{
    public static function renderAsArray(SampleTypeTemplate $template)
    {
        $array = [
            'name' => $template->getName(),
        ];

        if (!empty($template->getAttributes())) {
            $array['attributes'] = [];

            foreach ($template->getAttributes() as $attribute) {
                $array['attributes'][] = SampleTypeTemplateAttributeRender::renderAsArray($attribute);
            }
        }

        return $array;
    }

    public static function renderAsJson(SampleTypeTemplate $template)
    {
        return json_encode(self::renderAsArray($template));
    }
}
