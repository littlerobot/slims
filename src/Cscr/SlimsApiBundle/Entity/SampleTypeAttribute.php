<?php

namespace Cscr\SlimsApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;

/**
 * @ORM\Table(name="sample_type_attribute")
 * @ORM\Entity()
 */
class SampleTypeAttribute implements Downloadable
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
     * @var SampleType
     *
     * @ORM\ManyToOne(targetEntity="SampleType", inversedBy="attributes")
     * @ORM\JoinColumn(name="sample_type_id", nullable=false)
     */
    private $parent;

    /**
     * @var SampleTypeTemplateAttribute
     *
     * @ORM\ManyToOne(targetEntity="SampleTypeTemplateAttribute")
     * @ORM\JoinColumn(name="sample_type_template_attribute_id")
     *
     * @JMS\Exclude()
     */
    private $template;

    /**
     * @return int
     *
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("sample_type_template")
     */
    public function getSampleTypeTemplateAttribute()
    {
        return $this->template->getId();
    }

    public function getNonBinaryValue()
    {
        if ($this->filename) {
            return;
        }

        return $this->getValue();
    }

    /**
     * @return null|string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getBinaryContent()
    {
        if (!$this->getFilename()) {
            throw new \RuntimeException('Cannot get file content for non-file attributes.');
        }

        return base64_decode($this->value);
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return SampleTypeTemplateAttribute
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param SampleTypeTemplateAttribute $template
     *
     * @return SampleTypeAttribute
     */
    public function setTemplate(SampleTypeTemplateAttribute $template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        if ($this->getTemplate()->isDate() && null !== $this->value) {
            $transformer = new DateTimeToStringTransformer(null, null, 'Y-m-d');

            return $transformer->reverseTransform($this->value)->format('d/m/Y');
        }

        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return SampleTypeAttribute
     */
    public function setValue($value)
    {
        if ($this->getTemplate()->isDate() && null !== $value) {
            $transformer = new DateTimeToStringTransformer(null, null, 'd/m/Y');
            $value = $transformer->reverseTransform($value)->format('Y-m-d');
        }

        $this->value = $value;

        return $this;
    }

    /**
     * @param null|string $filename
     *
     * @return SampleTypeAttribute
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @param null|string $mimeType
     *
     * @return SampleTypeAttribute
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

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
     * @param SampleType $parent
     *
     * @return SampleTypeAttribute
     */
    public function setParent(SampleType $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get the label text, determined by the label of the associated {@see SampleTypeTemplate}.
     *
     * @return string
     *
     * @JMS\VirtualProperty()
     */
    public function getLabel()
    {
        return $this->template->getLabel();
    }

    /**
     * @return bool
     */
    public function isDocument()
    {
        return SampleTypeTemplateAttribute::TYPE_DOCUMENT === $this->template->getType();
    }

    /**
     * @return null|string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
