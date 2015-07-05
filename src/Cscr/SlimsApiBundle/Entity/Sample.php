<?php

namespace Cscr\SlimsApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table(name="sample")
 * @ORM\Entity()
 */
class Sample
{
    const STATE_STORED = 'stored';
    const STATE_REMOVED = 'removed';

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @JMS\Exclude()
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    private $colour;

    /**
     * @var Container
     *
     * @ORM\ManyToOne(targetEntity="Container", inversedBy="samples")
     * @ORM\JoinColumn(name="container_id")
     *
     * @JMS\Exclude()
     */
    private $container;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     *
     * @JMS\Exclude()
     */
    private $row;

    /**
     * @var int
     *
     * @ORM\Column(name="`column`", type="integer")
     *
     * @JMS\Exclude()
     */
    private $column;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=10)
     *
     * @JMS\Exclude()
     */
    private $position;

    /**
     * @var SampleType
     *
     * @ORM\ManyToOne(targetEntity="SampleType",)
     * @ORM\JoinColumn(name="sample_type_id", nullable=false)
     */
    private $type;

    /**
     * @var SampleInstanceTemplate
     *
     * @ORM\ManyToOne(targetEntity="SampleInstanceTemplate")
     * @ORM\JoinColumn(name="sample_instance_template_id", nullable=false)
     */
    private $template;

    /**
     * @var ArrayCollection<SampleInstanceAttribute>
     *
     * @ORM\OneToMany(targetEntity="SampleInstanceAttribute", mappedBy="parent", cascade={"PERSIST"})
     */
    private $attributes;

    /**
     * @var string "stored" or "removed"
     *
     * @ORM\Column(type="string", length=7)
     *
     * @JMS\Exclude()
     */
    private $state;

    public function __construct()
    {
        $this->attributes = new ArrayCollection();
    }

    public function setPosition($row, $column)
    {
        // FIXME: Yucky way to set values until it's decoupled from form component.
        if (is_null($row) || is_null($column)) {
            return $this;
        }

        $this->row = $row;
        $this->column = $column;
        $this->position = sprintf('%d:%d', $row, $column);

        return $this;
    }

    /**
     * @return string
     */
    public function getColour()
    {
        return $this->colour;
    }

    /**
     * @return int
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * @return int
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @return SampleType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return SampleInstanceTemplate
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return ArrayCollection
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param string $colour
     * @return Sample
     */
    public function setColour($colour)
    {
        $this->colour = $colour;

        return $this;
    }

    /**
     * @param Container $container
     * @return Sample
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @param int $row
     * @return Sample
     */
    public function setRow($row)
    {
        $this->row = $row;

        // FIXME: Yucky way to set values until it's decoupled from form component.
        $this->setPosition($this->row, $this->column);

        return $this;
    }

    /**
     * @param int $column
     * @return Sample
     */
    public function setColumn($column)
    {
        $this->column = $column;

        // FIXME: Yucky way to set values until it's decoupled from form component.
        $this->setPosition($this->row, $this->column);

        return $this;
    }

    /**
     * @param SampleType $type
     * @return Sample
     */
    public function setType(SampleType $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param SampleInstanceTemplate $template
     * @return Sample
     */
    public function setTemplate(SampleInstanceTemplate $template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @param ArrayCollection<SampleInstanceAttribute> $attributes
     * @return Sample
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;

        /** @var SampleInstanceAttribute $attribute */
        foreach ($attributes as $attribute) {
            $attribute->setParent($this);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @return Sample
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }
}
