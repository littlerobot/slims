<?php

namespace Cscr\SlimsApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table(name="sample_type")
 * @ORM\Entity(repositoryClass="Cscr\SlimsApiBundle\Entity\Repository\SampleTypeRepository")
 */
class SampleType
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
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var SampleTypeTemplate
     *
     * @ORM\ManyToOne(targetEntity="SampleTypeTemplate", inversedBy="sampleTypes")
     * @ORM\JoinColumn(name="sample_type_template_id")
     *
     * @JMS\Exclude()
     */
    private $template;

    /**
     * @var SampleTypeAttribute[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *  targetEntity="SampleTypeAttribute",
     *  mappedBy="parent",
     *  cascade={"PERSIST"}
     * )
     *
     * @JMS\Groups({"sample_types"})
     */
    private $attributes;

    public function __construct()
    {
        $this->attributes = new ArrayCollection();
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * @return SampleTypeTemplate
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param SampleTypeTemplate $template
     * @return SampleType
     */
    public function setTemplate(SampleTypeTemplate $template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return int
     *
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("sample_type_template")
     */
    public function getSampleTypeTemplateId()
    {
        return $this->template->getId();
    }

    /**
     * @return string
     *
     * @JMS\VirtualProperty()
     * @JMS\Groups({"samples"})
     * @JMS\SerializedName("sample_type_template_name")
     */
    public function getSampleTypeTemplateName()
    {
        return $this->template->getName();
    }

    /**
     * @return SampleTypeAttribute[]|ArrayCollection
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param SampleTypeAttribute $attribute
     * @return $this
     */
    public function addAttribute(SampleTypeAttribute $attribute)
    {
        if (!$this->getAttributes()->contains($attribute)) {
            $this->getAttributes()->add($attribute);
            $attribute->setParent($this);
        }

        return $this;
    }

    /**
     * @param SampleTypeAttribute $attribute
     * @return $this
     */
    public function removeAttribute(SampleTypeAttribute $attribute)
    {
        $this->getAttributes()->removeElement($attribute);

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
