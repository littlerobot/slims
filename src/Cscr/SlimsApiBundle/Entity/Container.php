<?php

namespace Cscr\SlimsApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table(name="container")
 * @ORM\Entity(repositoryClass="Cscr\SlimsApiBundle\Entity\Repository\ContainerRepository")
 */
class Container
{
    const STORES_CONTAINERS = 'containers';
    const STORES_SAMPLES = 'samples';

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var Container
     *
     * @ORM\ManyToOne(targetEntity="Container", inversedBy="children")
     *
     * @JMS\Exclude()
     */
    private $parent;

    /**
     * @var ResearchGroup
     *
     * @ORM\ManyToOne(targetEntity="ResearchGroup", fetch="EAGER")
     * @ORM\JoinColumn(name="research_group_id")
     */
    private $researchGroup;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $rows;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $columns;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20)
     */
    private $stores;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    private $colour;

    /**
     * @var ArrayCollection<Container>
     *
     * @ORM\OneToMany(targetEntity="Container", mappedBy="parent", fetch="EAGER")
     *
     * @JMS\SerializedName("data")
     */
    private $children;

    /**
     * @var ArrayCollection<Sample>
     *
     * @ORM\OneToMany(targetEntity="Sample", mappedBy="container", indexBy="position", cascade={"PERSIST"})
     *
     * @JMS\Exclude()
     */
    private $samples;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->samples = new ArrayCollection();
    }

    /**
     * A container is a leaf if it stores samples; it does not have any children.
     *
     * @return bool
     *
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("leaf")
     */
    public function isLeaf()
    {
        return static::STORES_SAMPLES === $this->stores;
    }

    /**
     * Returns the remaining sample capacity of all the children (and, if appropriate, their children) of this
     * container.
     *
     * @return int The total remaining sample capacity of all child containers.
     *
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("sample_remaining_capacity")
     */
    public function getSampleRemainingCapacity()
    {
        return $this->getTotalSampleCapacity() - $this->getNumberOfStoredSamples();
    }

    /**
     * Returns the sample capacity of all the children (and, if appropriate, their children) of this container.
     *
     * @return int The total sample capacity of all child containers.
     *
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("sample_total_capacity")
     */
    public function getTotalSampleCapacity()
    {
        // This container stores samples so won't have any children.
        if (static::STORES_SAMPLES === $this->stores) {
            return $this->rows * $this->columns;
        }

        // Container doesn't store samples so may have children that do.
        $childCapacity = 0;

        foreach ($this->children as $child) {
            $childCapacity += $child->getTotalSampleCapacity();
        }

        return $childCapacity;
    }

    /**
     * Returns the number of samples stored all the children (and, if appropriate, their children) of this container.
     *
     * @return int The total number of samples stored in all child containers.
     *
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("samples_stored")
     */
    public function getNumberOfStoredSamples()
    {
        // This container stores samples so won't have any children.
        if (static::STORES_SAMPLES === $this->stores) {
            return $this->samples->count();
        }

        $childStored = 0;

        foreach ($this->children as $child) {
            $childStored += $child->getNumberOfStoredSamples();
        }

        return $childStored;
    }

    /**
     * Store a child container inside this one.
     *
     * Will not add a container that is already a child of this container. There are no checks to ensure the container
     * is not already stored elsewhere.
     *
     * @param Container $container
     *
     * @return $this
     */
    public function storeContainerInside(Container $container)
    {
        if ($this->isLeaf()) {
            throw new \LogicException('A container cannot store other containers if it is configured to store samples.');
        }

        if (!$this->children->contains($container)) {
            $this->children->add($container);
            $container->parent = $this;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Calculates the parent hierarchy of the current container.
     *
     * Only traverses up the hierarchy - child containers will NOT be included.
     *
     * @return array
     */
    public function getContainerHierarchy()
    {
        $containers = [];

        $container = $this;
        $containers[] = $container;

        while ($container = $container->getParent()) {
            $containers[] = $container;
        }

        // The containers will be in reverse order as we traverse backwards. Fix that.
        return array_reverse($containers);
    }

    /**
     * @param string $name
     *
     * @return Container
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Container
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return ResearchGroup
     */
    public function getResearchGroup()
    {
        return $this->researchGroup;
    }

    /**
     * Specify the owner of the container.
     *
     * @param ResearchGroup $researchGroup The research group.
     *
     * @return $this
     */
    public function setResearchGroup(ResearchGroup $researchGroup)
    {
        $this->researchGroup = $researchGroup;

        return $this;
    }

    /**
     * @return int
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param int $rows
     *
     * @return Container
     */
    public function setRows($rows)
    {
        $this->rows = $rows;

        return $this;
    }

    /**
     * @return int
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param int $columns
     *
     * @return Container
     */
    public function setColumns($columns)
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * @return string
     */
    public function getStores()
    {
        return $this->stores;
    }

    /**
     * @param string $stores
     *
     * @return Container
     */
    public function setStores($stores)
    {
        $this->stores = $stores;

        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Specify a comment for the container.
     *
     * @param string $comment
     *
     * @return $this
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

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
     * Specify the colour of the container.
     *
     * @param string $colour A hexadecimal colour, prefixed with #
     *
     * @return $this
     */
    public function setColour($colour)
    {
        $this->colour = $colour;

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public static function getValidContainerTypes()
    {
        return [
            self::STORES_CONTAINERS,
            self::STORES_SAMPLES,
        ];
    }

    public function getSamples()
    {
        return $this->samples;
    }

    public function addSample(Sample $sample)
    {
        if (!$this->getSamples()->contains($sample)) {
            $this->samples[$sample->getPosition()] = $sample;
            $sample->setContainer($this);
        }

        return $this;
    }

    public function removeSample(Sample $sample)
    {
        $this->samples->removeElement($sample);

        return $this;
    }
}
