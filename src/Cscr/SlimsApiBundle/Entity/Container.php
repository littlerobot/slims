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
     * @var integer
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
     * @var string
     *
     * @ORM\Column(name="research_group", type="string", length=255, nullable=true)
     */
    private $researchGroup;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $rows;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
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
     * @ORM\Column(type="string", length=6, nullable=true)
     */
    private $colour;

    /**
     * @var Container[]
     *
     * @ORM\OneToMany(targetEntity="Container", mappedBy="parent")
     *
     * @JMS\SerializedName("data")
     */
    private $children;

    public function __construct($name, $rows, $columns, $stores)
    {
        $this->name = $name;
        $this->rows = $rows;
        $this->columns = $columns;
        $this->stores = $stores;

        $this->children = new ArrayCollection();
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
     * Returns the sample capacity of all the children (and, if appropriate, their children) of this container.
     *
     * @return int The total sample capacity of all child containers.
     */
    public function getSampleCapacity()
    {
        // This container stores samples so won't have any children.
        if (static::STORES_SAMPLES === $this->stores) {
            return $this->rows * $this->columns;
        }

        // Container doesn't store samples so may have children that do.
        $childCapacity = 0;

        foreach ($this->children as $child) {
            $childCapacity += $child->getSampleCapacity();
        }

        return $childCapacity;
    }

    /**
     * The name of the container plus the sample storage capacity of its children and the number of samples stored.
     *
     * @return string
     *
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("name")
     */
    public function getNameIncludingCapacity()
    {
        return sprintf('%s (Samples: 0/%d)', $this->name, $this->getSampleCapacity());
    }

    /**
     * Store a child container inside this one.
     *
     * Will not add a container that is already a child of this container. There are no checks to ensure the container
     * is not already stored elsewhere.
     *
     * @param Container $container
     * @return $this
     */
    public function storeContainerInside(Container $container)
    {
        if (!$this->children->contains($container)) {
            $this->children->add($container);
            $container->parent = $this;
        }

        return $this;
    }

    /**
     * Specify the owner of the container.
     *
     * @param string $researchGroup Research group name.
     * @return $this
     */
    public function specifyOwner($researchGroup)
    {
        $this->researchGroup = $researchGroup;
        return $this;
    }

    /**
     * Specify a comment for the container.
     *
     * @param string $comment
     * @return $this
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * Specify the colour of the container.
     *
     * @param string $colour A hexadecimal colour, prefixed with #
     * @return $this
     */
    public function setColour($colour)
    {
        $this->colour = $colour;
        return $this;
    }
}
