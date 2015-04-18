<?php

namespace Cscr\SlimsApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class SampleInstanceTemplateStoredAttribute extends AbstractSampleInstanceTemplateAttribute
{
    /**
     * @var SampleInstanceTemplate
     *
     * @ORM\ManyToOne(targetEntity="SampleInstanceTemplate", inversedBy="storedAttributes")
     * @ORM\JoinColumn(name="sample_instance_template_id")
     */
    protected $parent;
}
