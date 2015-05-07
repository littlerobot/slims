<?php

namespace Cscr\SlimsApiBundle\Tests\Renderer;

use Cscr\SlimsApiBundle\Entity\AbstractSampleInstanceTemplateAttribute;
use Cscr\SlimsApiBundle\Entity\SampleInstanceTemplate;
use Doctrine\Common\Collections\ArrayCollection;

class SampleInstanceTemplateRenderer
{
    public static function renderAsJson(SampleInstanceTemplate $template)
    {
        return json_encode(self::renderAsArray($template));
    }

    private static function renderAsArray(SampleInstanceTemplate $template)
    {
        $array = [
            'name' => $template->getName(),
        ];

        $storedAttributes = self::renderAttributesAsArray($template->getStoredAttributes());
        if (!empty($storedAttributes)) {
            $array['stored'] = $storedAttributes;
        }

        $removedAttributes = self::renderAttributesAsArray($template->getRemovedAttributes());
        if (!empty($removedAttributes)) {
            $array['removed'] = $removedAttributes;
        }

        return $array;
    }

    /**
     * @param AbstractSampleInstanceTemplateAttribute[]|ArrayCollection $attributes
     * @return array
     */
    private static function renderAttributesAsArray(ArrayCollection $attributes)
    {
        $array = [];

        foreach ($attributes as $attribute) {
            $array[] = SampleInstanceTemplateAttributeRenderer::renderAsArray($attribute);
        }
        return $array;
    }
}
