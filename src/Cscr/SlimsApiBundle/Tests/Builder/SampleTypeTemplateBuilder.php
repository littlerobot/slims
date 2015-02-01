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
     * @var ArrayCollection|SampleTypeAttributeBuilder[]
     */
    public $attributes;

    public function __construct()
    {
        $this->attributes = new ArrayCollection();
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
     * @param SampleTypeAttributeBuilder $builder
     * @return $this
     */
    public function withAttribute(SampleTypeAttributeBuilder $builder)
    {
        $this->attributes->add($builder);
        return $this;
    }

    /**
     * @param  string $name
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
    public function buildAsArray()
    {
        $object = $this->build();

        $array = [
            'name' => $object->getName(),
        ];

        if ($this->attributes) {
            $array['attributes'] = [];

            foreach ($this->attributes as $attribute) {
                $array['attributes'][] = $attribute->buildAsArray();
            }
        }

        return $array;
    }

    /**
     * @return string
     */
    public function buildAsJson()
    {
        return json_encode($this->buildAsArray());
    }

    /**
     * @return array|null
     */
    private function buildAttributes()
    {
        if (!$this->attributes) {
            return null;
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
        $firstOrder = $this->attributes->first()->order;
        $lastOrder = $this->attributes->last()->order;

        $this->attributes->first()->withOrder($lastOrder);
        $this->attributes->last()->withOrder($firstOrder);
    }
}
