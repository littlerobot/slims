<?php

namespace Cscr\SlimsApiBundle\Tests\Builder;

use Cscr\SlimsApiBundle\Entity\SampleInstanceTemplate;

class SampleInstanceTemplateBuilder
{
    const TYPE_STORED = 'storedAttributes';
    const TYPE_REMOVED = 'removedAttributes';

    private $name;
    private $storedAttributes = [];
    private $removedAttributes = [];

    /**
     * @return SampleInstanceTemplate
     */
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
     * @param string                                   $type
     * @param SampleInstanceTemplateAttributeBuilder[] $attributes
     *
     * @return array|null
     */
    private function buildAttributes($type, array $attributes)
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

    /**
     * @param string $name
     *
     * @return $this
     */
    public function withName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param SampleInstanceTemplateAttributeBuilder $attribute
     *
     * @return $this
     */
    public function withStoredAttribute(SampleInstanceTemplateAttributeBuilder $attribute)
    {
        $this->storedAttributes[] = $attribute;

        return $this;
    }

    /**
     * @param SampleInstanceTemplateAttributeBuilder $attribute
     *
     * @return $this
     */
    public function withRemovedAttribute(SampleInstanceTemplateAttributeBuilder $attribute)
    {
        $this->removedAttributes[] = $attribute;

        return $this;
    }

    /**
     * Shuffle the stored and removed attributes.
     *
     * @return $this
     */
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

    /**
     * @param string $name Template name
     *
     * @return SampleInstanceTemplateBuilder
     */
    public static function buildBuilderWithAllAttributeTypes($name)
    {
        return (new self())
            ->withName($name)
            ->withStoredAttribute(SampleInstanceTemplateAttributeBuilder::aBriefTextAttribute()->withOrder(1))
            ->withStoredAttribute(SampleInstanceTemplateAttributeBuilder::aLongTextAttribute()->withOrder(2))
            ->withStoredAttribute(SampleInstanceTemplateAttributeBuilder::anOptionAttribute()->withOrder(3))
            ->withStoredAttribute(SampleInstanceTemplateAttributeBuilder::aDocumentAttribute()->withOrder(4))
            ->withStoredAttribute(SampleInstanceTemplateAttributeBuilder::aDateAttribute()->withOrder(5))
            ->withStoredAttribute(SampleInstanceTemplateAttributeBuilder::aColourAttribute()->withOrder(6))
            ->withStoredAttribute(SampleInstanceTemplateAttributeBuilder::aUserAttribute()->withOrder(7))
            ->withRemovedAttribute(SampleInstanceTemplateAttributeBuilder::aBriefTextAttribute()->withOrder(1))
            ->withRemovedAttribute(SampleInstanceTemplateAttributeBuilder::aLongTextAttribute()->withOrder(2))
            ->withRemovedAttribute(SampleInstanceTemplateAttributeBuilder::anOptionAttribute()->withOrder(3))
            ->withRemovedAttribute(SampleInstanceTemplateAttributeBuilder::aDocumentAttribute()->withOrder(4))
            ->withRemovedAttribute(SampleInstanceTemplateAttributeBuilder::aDateAttribute()->withOrder(5))
            ->withRemovedAttribute(SampleInstanceTemplateAttributeBuilder::aColourAttribute()->withOrder(6))
            ->withRemovedAttribute(SampleInstanceTemplateAttributeBuilder::aUserAttribute()->withOrder(7));
    }
}
