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
     *
     * @JMS\Exclude()
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
     * @var Container[]
     *
     * @ORM\OneToMany(targetEntity="Container", mappedBy="parent")
     */
    private $children;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }
}
