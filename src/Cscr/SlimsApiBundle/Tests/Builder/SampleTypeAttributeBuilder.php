<?php

namespace Cscr\SlimsApiBundle\Tests\Builder;

use Cscr\SlimsApiBundle\Entity\SampleTypeAttribute;

class SampleTypeAttributeBuilder
{
    public $label = 'Attribute';
    public $order;
    public $type = SampleTypeAttribute::TYPE_BRIEF_TEXT;
    public $options;

    /**
     * @return SampleTypeAttribute
     */
    public function build()
    {
        return (new SampleTypeAttribute())
            ->setLabel($this->label)
            ->setOrder($this->order)
            ->setType($this->type)
            ->setOptions($this->options)
        ;
    }

    /**
     * Build a brief text attribute.
     *
     * @return $this
     */
    public static function aBriefTextAttribute()
    {
        return (new self())
            ->withLabel('Brief text')
            ->withType(SampleTypeAttribute::TYPE_BRIEF_TEXT)
        ;
    }

    /**
     * Build a long text attribute.
     *
     * @return $this
     */
    public static function aLongTextAttribute()
    {
        return (new self())
            ->withLabel('Long text')
            ->withType(SampleTypeAttribute::TYPE_LONG_TEXT)
        ;
    }

    /**
     * Build an option attribute.
     *
     * @return $this
     */
    public static function anOptionAttribute()
    {
        return (new self())
            ->withLabel('Option')
            ->withType(SampleTypeAttribute::TYPE_OPTION)
            ->withOptions([
                'First',
                'Second',
            ])
        ;
    }

    /**
     * Build a document attribute.
     *
     * @return $this
     */
    public static function aDocumentAttribute()
    {
        return (new self())
            ->withLabel('Document')
            ->withType(SampleTypeAttribute::TYPE_DOCUMENT)
        ;
    }

    /**
     * Build a date attribute.
     *
     * @return $this
     */
    public static function aDateAttribute()
    {
        return (new self())
            ->withLabel('Date')
            ->withType(SampleTypeAttribute::TYPE_DATE)
        ;
    }

    /**
     * Build a colour attribute.
     *
     * @return $this
     */
    public static function aColourAttribute()
    {
        return (new self())
            ->withLabel('Colour')
            ->withType(SampleTypeAttribute::TYPE_COLOUR)
        ;
    }

    /**
     * Build a user attribute.
     *
     * @return $this
     */
    public static function aUserAttribute()
    {
        return (new self())
            ->withLabel('User')
            ->withType(SampleTypeAttribute::TYPE_USER)
        ;
    }

    /**
     * @param string $label
     * @return $this
     */
    public function withLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function withType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function withOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Build an attribute with no options.
     *
     * @return $this
     */
    public function withoutOptions()
    {
        $this->options = null;
        return $this;
    }

    /**
     * @param int $order
     * @return $this
     */
    public function withOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @return array
     */
    public function buildAsArray()
    {
        $template = $this->build();

        $array = [
            'label' => $template->getLabel(),
            'order' => $template->getOrder(),
            'type' => $template->getType(),
        ];

        if ($template->getOptions()) {
            $array['options'] = $template->getOptions();
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
}
