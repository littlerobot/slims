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
     * @ORM\Column(type="integer")
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
     * @ORM\OneToMany(targetEntity="SampleInstanceAttribute", mappedBy="parent")
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

    public function setPosition($row, $column)
    {
        $this->row = $row;
        $this->column = $column;
        $this->position = sprintf('%d:%d', $row, $column);
    }
}
