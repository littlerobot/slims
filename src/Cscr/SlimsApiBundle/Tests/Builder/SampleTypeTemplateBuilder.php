<?php

namespace Cscr\SlimsApiBundle\Tests\Builder;

use Cscr\SlimsApiBundle\Entity\SampleTypeTemplate;
use Doctrine\Common\Collections\ArrayCollection;

class SampleTypeTemplateBuilder
{
    /**
     * @var string
     */
    public $name = 'Template';

    /**
     * @var ArrayCollection|SampleTypeTemplateAttributeBuilder[]
     */
    public $attributes;

    public function __construct()
    {
        $this->attributes = new ArrayCollection();
    }

    /**
     * @param string $name
     *
     * @return SampleTypeTemplateBuilder
     */
    public static function buildBasicSampleTypeTemplateWithAttributes($name)
    {
        $template = (new self())
            ->withName($name)
            ->withAttribute(SampleTypeTemplateAttributeBuilder::aBriefTextAttribute())
            ->withAttribute(SampleTypeTemplateAttributeBuilder::aLongTextAttribute());

        return $template;
    }

    /**
     * @return SampleTypeTemplate
     */
    public function build()
    {
        $template = (new SampleTypeTemplate())
            ->setName($this->name);

        foreach ($this->buildAttributes() as $attribute) {
            $template->addAttribute($attribute);
        }

        return $template;
    }

    /**
     * @param SampleTypeTemplateAttributeBuilder $builder
     *
     * @return $this
     */
    public function withAttribute(SampleTypeTemplateAttributeBuilder $builder)
    {
        $this->attributes->add($builder);

        return $this;
    }

    /**
     * @param string $name
     *
     * @return SampleTypeTemplateBuilder
     */
    public function withName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return array
     */
    private function buildAttributes()
    {
        if (!$this->attributes) {
            return [];
        }

        $attributes = [];

        foreach ($this->attributes as $attribute) {
            $attributes[] = $attribute->build();
        }

        return $attributes;
    }

    /**
     * Switch the specified order of the first and last attributes.
     *
     * NOTE: Does not change the order of the array.
     */
    public function switchOrderOfFirstAndLastAttributes()
    {
        $firstOrder = $this->attributes->first()->build()->getOrder();
        $lastOrder = $this->attributes->last()->build()->getOrder();

        $this->attributes->first()->withOrder($lastOrder);
        $this->attributes->last()->withOrder($firstOrder);
    }
}
