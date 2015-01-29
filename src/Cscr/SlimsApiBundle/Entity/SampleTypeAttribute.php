<?php

namespace Cscr\SlimsApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="sample_type_attribute")
 * @ORM\Entity()
 */
class SampleTypeAttribute
{
    const TYPE_BRIEF_TEXT = 'brief-text';
    const TYPE_LONG_TEXT = 'long-text';
    const TYPE_OPTION = 'option';
    const TYPE_DOCUMENT = 'document';
    const TYPE_DATE = 'date';
    const TYPE_COLOUR = 'colour';
    const TYPE_USER = 'user';

    /**
     * @var integer
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="sequence", type="smallint")
     */
    private $order;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @var ArrayCollection|string[]
     *
     * @ORM\Column(type="array", nullable=true)
     */
    private $options;

    /**
     * @var SampleTypeTemplate
     *
     * @ORM\ManyToOne(targetEntity="SampleTypeTemplate", inversedBy="attributes")
     */
    private $parent;

    public function setParent(SampleTypeTemplate $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @param int $order
     * @return SampleTypeAttribute
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @param string $label
     * @return SampleTypeAttribute
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @param string $type
     * @return SampleTypeAttribute
     */
    public function setType($type)
    {
        $this->type = $type;

        // Remove any existing options if we're changing from an option type.
        if (self::TYPE_OPTION !== $this->getType()) {
            $this->setOptions(null);
        }

        return $this;
    }

    /**
     * @param array|\string[]|null $options
     * @return SampleTypeAttribute
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
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return ArrayCollection|\string[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return SampleTypeTemplate
     */
    public function getParent()
    {
        return $this->parent;
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
     * @return array All valid type options.
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
}
