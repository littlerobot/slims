<?php

namespace Cscr\SlimsApiBundle\Entity;

use Cscr\SlimsApiBundle\ValueObject\Colour;
use Cscr\SlimsApiBundle\ValueObject\SamplePosition;
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
     * @var string|null
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

    /**
     * @param SamplePosition $position
     *
     * @return $this
     */
    public function setPosition(SamplePosition $position)
    {
        $this->row = $position->getRow();
        $this->column = $position->getColumn();
        $this->position = $position->getAsCoordinates();

        return $this;
    }

    /**
     * @return Colour|null
     */
    public function getColour()
    {
        if (!$this->colour) {
            return;
        }

        return Colour::fromHex($this->colour);
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
     * @param Colour|null $colour
     *
     * @return Sample
     */
    public function setColour(Colour $colour = null)
    {
        $this->colour = $colour ? $colour->getAsHex() : null;

        return $this;
    }

    /**
     * @param Container $container
     *
     * @return Sample
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @param SampleType $type
     *
     * @return Sample
     */
    public function setType(SampleType $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param SampleInstanceTemplate $template
     *
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
     *
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
     * @return SamplePosition
     */
    public function getPosition()
    {
        return SamplePosition::fromCoordinates($this->position);
    }

    /**
     *
     */
    public function getIndex()
    {
        if (!$this->getContainer()) {
            throw new \LogicException('Cannot calculate the position of a sample that is not in a container.');
        }

        return ($this->container->getColumns() * $this->getRow()) + $this->getColumn() + 1;
    }

    /**
     * Returns a text representation of the sample hierarchy.
     *
     * E.g. Parent container → Child container [1], where [1] is the position of the sample.
     *
     * The position will be left padded to the length of the maximum position in the container.
     *
     * @return string
     */
    public function getHierarchy()
    {
        $container = $this->getContainer();

        $hierarchy = implode(
            ' → ',
            array_map(
                function (Container $container) {
                    return $container->getName();
                },
                $container->getContainerHierarchy()
            )
        );

        return sprintf(
            '%s [%s]',
            $hierarchy,
            str_pad($this->getIndex(), strlen($this->getContainer()->getTotalSampleCapacity()), 0, STR_PAD_LEFT)
        );
    }
}
