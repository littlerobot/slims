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
     * @var SampleInstanceTemplateStoredAttribute
     *
     * @ORM\ManyToOne(targetEntity="SampleInstanceTemplateStoredAttribute")
     * @ORM\JoinColumn(name="sample_instance_template_attribute_id", nullable=false)
     *
     * @JMS\Exclude()
     */
    private $template;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Sample $parent
     *
     * @return SampleInstanceAttribute
     */
    public function setParent(Sample $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return SampleInstanceTemplateStoredAttribute
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param SampleInstanceTemplateStoredAttribute $template
     *
     * @return SampleInstanceAttribute
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return SampleInstanceAttribute
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param null|string $mimeType
     *
     * @return SampleInstanceAttribute
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param null|string $filename
     *
     * @return SampleInstanceAttribute
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    public function getNonBinaryValue()
    {
        if ($this->filename) {
            return;
        }

        return $this->value;
    }
}
