<?php

namespace Cscr\SlimsApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table(name="sample_type_template")
 * @ORM\Entity()
 */
class SampleTypeTemplate
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
     * @var ArrayCollection|SampleTypeTemplateAttribute[]
     *
     * @ORM\OneToMany(
     *  targetEntity="SampleTypeTemplateAttribute",
     *  mappedBy="parent",
     *  cascade={"PERSIST"}
     * )
     */
    private $attributes;

    /**
     * @var SampleType[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="SampleType", mappedBy="template")
     *
     * @JMS\Exclude()
     */
    private $sampleTypes;

    public function __construct()
    {
        $this->attributes = new ArrayCollection();
        $this->sampleTypes = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * @param SampleTypeTemplateAttribute $attribute
     * @return $this
     */
    public function addAttribute(SampleTypeTemplateAttribute $attribute)
    {
        if (!$this->getAttributes()->contains($attribute)) {
            $this->getAttributes()->add($attribute);
            $attribute->setParent($this);
        }

        return $this;
    }

    /**
     * @param SampleTypeTemplateAttribute $attribute
     * @return $this
     */
    public function removeAttribute(SampleTypeTemplateAttribute $attribute)
    {
        $this->getAttributes()->removeElement($attribute);

        return $this;
    }

    /**
     * @return SampleTypeTemplateAttribute[]|ArrayCollection
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * The template can be edited as long as no samples have information saved using it.
     *
     * @return bool true if the template can be edited, false otherwise.
     *
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("editable")
     */
    public function isEditable()
    {
        // TODO: Check for samples that have been recorded against this template and return false if there are any.
        return true;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
