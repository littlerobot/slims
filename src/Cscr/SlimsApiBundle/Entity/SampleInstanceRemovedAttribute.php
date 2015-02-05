<?php

namespace Cscr\SlimsApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity()
 */
class SampleInstanceRemovedAttribute extends AbstractSampleInstanceAttribute
{
    /**
     * @var SampleInstanceTemplate
     *
     * @ORM\ManyToOne(targetEntity="SampleInstanceTemplate", inversedBy="removedAttributes")
     * @ORM\JoinColumn(name="sample_instance_template_id")
     */
    protected $parent;
}
