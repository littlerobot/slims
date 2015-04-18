<?php

namespace Cscr\SlimsApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table(name="sample_instance_attribute")
 * @ORM\Entity()
 */
class SampleInstanceAttribute
{
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
     * @ORM\Column(type="text", nullable=true)
     *
     * @JMS\Accessor(getter="getNonBinaryValue")
     */
    private $value;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    private $filename;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true, name="mime_type")
     */
    private $mimeType;

    /**
     * The URL to download the document.
     *
     * This is only populated for documents.
     *
     * @var string|null
     */
    private $url;

    /**
     * @var Sample
     *
     * @ORM\ManyToOne(targetEntity="Sample", inversedBy="attributes")
     * @ORM\JoinColumn(name="sample_id", nullable=false)
     *
     * @JMS\Exclude()
     */
    private $parent;

    /**
     * @var AbstractSampleInstanceTemplateAttribute
     *
     * @ORM\ManyToOne(targetEntity="AbstractSampleInstanceTemplateAttribute")
     * @ORM\JoinColumn(name="sample_instance_template_attribute_id", nullable=false)
     *
     * @JMS\Exclude()
     */
    private $template;

    public function getNonBinaryValue()
    {
        if ($this->filename) {
            return null;
        }

        return $this->value;
    }
}
