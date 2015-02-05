<?php

namespace Cscr\SlimsApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 *
 * @ORM\Table("sample_instance_attribute")
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="activity", type="string")
 * @ORM\DiscriminatorMap({"remove"="SampleInstanceRemovedAttribute", "store"="SampleInstanceStoredAttribute"})
 * @JMS\Discriminator(field="activity", map={
 *  "remove": "Cscr\SlimsApiBundle\Entity\SampleInstanceRemovedAttribute",
 *  "store": "Cscr\SlimsApiBundle\Entity\SampleInstanceStoredAttribute"
 * })
 */
abstract class AbstractSampleInstanceAttribute
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    protected $id;

    /**
     * @var integer
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
     * @var array|string[]
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $options;

    /**
     * @var SampleInstanceTemplate
     */
    protected $parent;

    /**
     * Get id
     *
     * @return integer
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
     * @return SampleInstanceRemovedAttribute
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
     * @return SampleInstanceRemovedAttribute
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
     * @return SampleInstanceRemovedAttribute
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return array|\string[]
     */
    public function getOptions()
    {
        return $this->options;
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
     * @return SampleInstanceTemplate
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param SampleInstanceTemplate $parent
     * @return SampleInstanceRemovedAttribute
     */
    public function setParent(SampleInstanceTemplate $parent)
    {
        $this->parent = $parent;
        return $this;
    }
}
