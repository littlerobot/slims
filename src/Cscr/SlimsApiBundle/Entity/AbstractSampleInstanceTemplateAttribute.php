<?php

namespace Cscr\SlimsApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table("sample_instance_template_attribute")
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="activity", type="string")
 * @ORM\DiscriminatorMap({
 *  "remove"="SampleInstanceTemplateRemovedAttribute",
 *  "store"="SampleInstanceTemplateStoredAttribute"
 * })
 * @JMS\Discriminator(field="activity", map={
 *  "remove": "Cscr\SlimsApiBundle\Entity\SampleInstanceTemplateRemovedAttribute",
 *  "store": "Cscr\SlimsApiBundle\Entity\SampleInstanceTemplateStoredAttribute"
 * })
 */
abstract class AbstractSampleInstanceTemplateAttribute
{
    const TYPE_BRIEF_TEXT = 'brief-text';
    const TYPE_LONG_TEXT = 'long-text';
    const TYPE_OPTION = 'option';
    const TYPE_DOCUMENT = 'document';
    const TYPE_DATE = 'date';
    const TYPE_COLOUR = 'colour';
    const TYPE_USER = 'user';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var int
     *
     * @ORM\Column(name="sequence", type="smallint")
     */
    protected $order;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $label;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $type;

    /**
     * @var string[]|null
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $options;

    /**
     * @var SampleInstanceTemplate
     */
    protected $parent;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int $order
     *
     * @return SampleInstanceTemplateRemovedAttribute
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return SampleInstanceTemplateRemovedAttribute
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return SampleInstanceTemplateRemovedAttribute
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param string[]|null $options
     *
     * @return AbstractSampleInstanceTemplateAttribute
     */
    public function setOptions(array $options = null)
    {
        // Remove any "blank" elements
        if (is_array($options)) {
            $options = array_filter($options);
        }

        // Stop an empty array being set as this will make the output a bit funky.
        if (empty($options)) {
            $this->options = null;

            return $this;
        }

        $this->options = $options;

        return $this;
    }

    /**
     * @return SampleInstanceTemplate
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param SampleInstanceTemplate $parent
     *
     * @return SampleInstanceTemplateRemovedAttribute
     */
    public function setParent(SampleInstanceTemplate $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Does this attribute type allow options to be specified?
     *
     * @return bool true if it does, false if it does not.
     */
    public function allowsOptionsToBeSpecified()
    {
        return self::TYPE_OPTION === $this->getType();
    }

    /**
     * @return string[] All valid type options.
     */
    public static function getValidChoices()
    {
        return [
            self::TYPE_BRIEF_TEXT,
            self::TYPE_COLOUR,
            self::TYPE_DATE,
            self::TYPE_DOCUMENT,
            self::TYPE_LONG_TEXT,
            self::TYPE_OPTION,
            self::TYPE_USER,
        ];
    }

    /**
     * @return bool
     */
    public function isDate()
    {
        return self::TYPE_DATE === $this->getType();
    }
}
