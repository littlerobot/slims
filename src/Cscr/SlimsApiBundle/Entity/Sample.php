<?php

namespace Cscr\SlimsApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table(name="sample")
 * @ORM\Entity(repositoryClass="Cscr\SlimsApiBundle\Entity\Repository\SampleRepository")
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
     * @var SampleInstanceAttribute[]|ArrayCollection
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
    private $state = self::STATE_STORED;

    public function __construct()
    {
        $this->attributes = new ArrayCollection();
    }

    public function setPosition($position)
    {
        if (false === stripos($position, ':')) {
            throw new \RuntimeException('A sample position must include a colon, to separate the row and column.');
        }

        // FIXME: Yucky way to set values until it's decoupled from form component.
        list($row, $column) = explode(':', $position);

        $this->row = $row;
        $this->column = $column;
        $this->position = $position;

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
     * @return SampleInstanceAttribute[]|ArrayCollection
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
        $this->setPosition(sprintf('%d:%d', $this->row, $this->column));

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
        $this->setPosition(sprintf('%d:%d', $this->row, $this->column));

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

    public function addAttribute(SampleInstanceAttribute $attribute)
    {
        if (!$this->attributes->contains($attribute)) {
            $this->attributes->add($attribute);
            $attribute->setParent($this);
        }

        return $this;
    }

    public function removeAttribute(SampleInstanceAttribute $attribute)
    {
        $this->attributes->removeElement($attribute);

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

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }
}
