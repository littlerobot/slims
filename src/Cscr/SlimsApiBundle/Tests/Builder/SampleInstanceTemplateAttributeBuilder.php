<?php

namespace Cscr\SlimsApiBundle\Tests\Builder;

use Cscr\SlimsApiBundle\Entity\AbstractSampleInstanceTemplateAttribute;

class SampleInstanceTemplateAttributeBuilder
{
    private $order;
    private $label;
    private $type;
    private $options;

    const TYPE_STORED = 'SampleInstanceTemplateStoredAttribute';
    const TYPE_REMOVED = 'SampleInstanceTemplateRemovedAttribute';

    public function build($type)
    {
        /** @var AbstractSampleInstanceTemplateAttribute $attribute */
        $attribute = new $type();

        $attribute
            ->setOrder($this->order)
            ->setLabel($this->label)
            ->setType($this->type)
            ->setOptions($this->options)
        ;

        return $attribute;
    }

    public function withLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    public function withType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param array $options
     *
     * @return $this
     */
    public function withOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    public function withOrder($order)
    {
        $this->order = $order;

        return $this;
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
            ->withType(AbstractSampleInstanceTemplateAttribute::TYPE_BRIEF_TEXT);
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
            ->withType(AbstractSampleInstanceTemplateAttribute::TYPE_LONG_TEXT);
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
            ->withType(AbstractSampleInstanceTemplateAttribute::TYPE_OPTION)
            ->withOptions([
                'First',
                'Second',
            ]);
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
            ->withType(AbstractSampleInstanceTemplateAttribute::TYPE_DOCUMENT);
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
            ->withType(AbstractSampleInstanceTemplateAttribute::TYPE_DATE);
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
            ->withType(AbstractSampleInstanceTemplateAttribute::TYPE_COLOUR);
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
            ->withType(AbstractSampleInstanceTemplateAttribute::TYPE_USER);
    }
}
