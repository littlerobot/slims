<?php

namespace Cscr\SlimsApiBundle\Entity;

use Cscr\SlimsApiBundle\ValueObject\ResearchGroup;
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
     *
     * @JMS\Exclude()
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

    public function __construct()
    {
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
}
