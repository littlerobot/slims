<?php

namespace Cscr\SlimsApiBundle\Tests\Builder;

use Cscr\SlimsApiBundle\Entity\SampleType;
use Cscr\SlimsApiBundle\Entity\SampleTypeTemplate;
use Cscr\SlimsApiBundle\Entity\SampleTypeTemplateAttribute;

class SampleTypeBuilder
{
    private $name = 'Sample type';

    /** @var SampleTypeTemplate */
    private $template;

    /**
     * @var SampleTypeAttributeBuilder[]
     */
    private $attributes = [];

    public function __construct()
    {
        $this->template = new SampleTypeTemplate();
    }

    /**
     * @param SampleTypeTemplate $sampleTypeTemplate
     * @param string $name
     * @return SampleTypeBuilder
     */
    public static function buildSampleTypeBuilderWithAttributes(SampleTypeTemplate $sampleTypeTemplate, $name)
    {
        $builder = (new SampleTypeBuilder())
            ->withName($name)
            ->withTemplate($sampleTypeTemplate);

        foreach ($sampleTypeTemplate->getAttributes() as $attributeTemplate) {
            switch ($attributeTemplate->getType()) {
                case SampleTypeTemplateAttribute::TYPE_BRIEF_TEXT:
                default:
                    $attributeBuilder = SampleTypeAttributeBuilder::aBriefTextAttribute($attributeTemplate);
                    break;
                case SampleTypeTemplateAttribute::TYPE_LONG_TEXT:
                    $attributeBuilder = SampleTypeAttributeBuilder::aLongTextAttribute($attributeTemplate);
                    break;
            }

            $builder->withAttribute($attributeBuilder);
        }

        return $builder;
    }

    public function build()
    {
        $type = (new SampleType())
            ->setName($this->name)
            ->setTemplate($this->template);

        foreach ($this->buildAttributes($type) as $attribute) {
            $type->addAttribute($attribute);
        }

        return $type;
    }

    public function withName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function withTemplate(SampleTypeTemplate $template)
    {
        $this->template = $template;

        return $this;
    }

    public function withAttribute(SampleTypeAttributeBuilder $attribute)
    {
        $this->attributes[] = $attribute;

        return $this;
    }

    private function buildAttributes(SampleType $type)
    {
        $attributes = [];

        foreach ($this->attributes as $attribute) {
            $attributes[] = $attribute->build()->setParent($type);
        }

        return $attributes;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function __clone()
    {
        $attributes = [];

        foreach ($this->getAttributes() as $attribute) {
            $attributes[] = clone $attribute;
        }

        $this->attributes = $attributes;
    }
}
