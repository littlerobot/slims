<?php

namespace Cscr\SlimsApiBundle\Tests\Builder;

use Cscr\SlimsApiBundle\Entity\SampleInstanceTemplate;

class SampleInstanceTemplateBuilder
{
    const TYPE_STORED = 'storedAttributes';
    const TYPE_REMOVED = 'removedAttributes';

    private $name;
    private $storedAttributes;
    private $removedAttributes;

    public function build()
    {
        $template = new SampleInstanceTemplate();
        $template->setName($this->name);

        $storedType = SampleInstanceTemplateAttributeBuilder::TYPE_STORED;
        foreach ($this->buildAttributes($storedType, $this->storedAttributes) as $attribute) {
            $template->addStoredAttribute($attribute);
        }

        $removedType = SampleInstanceTemplateAttributeBuilder::TYPE_REMOVED;
        foreach ($this->buildAttributes($removedType, $this->removedAttributes) as $attribute) {
            $template->addRemovedAttribute($attribute);
        }

        return $template;
    }

    /**
     * @param $type
     * @param array|SampleInstanceTemplateAttributeBuilder[] $attributes
     * @return array|null
     */
    private function buildAttributes($type, array $attributes = null)
    {
        if (empty($this->$type)) {
            return [];
        }

        $builtAttributes = [];

        foreach ($attributes as $attribute) {
            $builtAttributes[] = $attribute->build($type);
        }

        return $builtAttributes;
    }

    public function withName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function withStoredAttribute(SampleInstanceTemplateAttributeBuilder $attribute)
    {
        $this->storedAttributes[] = $attribute;

        return $this;
    }

    public function withRemovedAttribute(SampleInstanceTemplateAttributeBuilder $attribute)
    {
        $this->removedAttributes[] = $attribute;

        return $this;
    }

    public function shuffleAttributes()
    {
        if (!empty($this->removedAttributes)) {
            shuffle($this->removedAttributes);
        }

        if (!empty($this->storedAttributes)) {
            shuffle($this->storedAttributes);
        }

        return $this;
    }
}
