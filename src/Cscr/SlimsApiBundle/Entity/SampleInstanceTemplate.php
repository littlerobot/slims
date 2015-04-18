<?php

namespace Cscr\SlimsApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="sample_instance_template")
 */
class SampleInstanceTemplate
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
     * @var ArrayCollection|SampleInstanceTemplateStoredAttribute[]
     *
     * @ORM\OneToMany(
     *  targetEntity="SampleInstanceTemplateStoredAttribute",
     *  mappedBy="parent",
     *  cascade={"PERSIST"}
     * )
     *
     * @JMS\SerializedName("store")
     */
    private $storedAttributes;

    /**
     * @var ArrayCollection|SampleInstanceTemplateRemovedAttribute[]
     *
     * @ORM\OneToMany(
     *  targetEntity="SampleInstanceTemplateRemovedAttribute",
     *  mappedBy="parent",
     *  cascade={"PERSIST"}
     * )
     *
     * @JMS\SerializedName("remove")
     */
    private $removedAttributes;

    public function __construct()
    {
        $this->storedAttributes = new ArrayCollection();
        $this->removedAttributes = new ArrayCollection();
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
     * @return SampleInstanceTemplate
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getStoredAttributes()
    {
        return $this->storedAttributes;
    }

    public function addStoredAttribute(SampleInstanceTemplateStoredAttribute $attribute)
    {
        if (!$this->getStoredAttributes()->contains($attribute)) {
            $this->getStoredAttributes()->add($attribute);
            $attribute->setParent($this);
        }

        return $this;
    }

    public function removeStoredAttribute(SampleInstanceTemplateStoredAttribute $attribute)
    {
        $this->getStoredAttributes()->removeElement($attribute);

        return $this;
    }

    public function getRemovedAttributes()
    {
        return $this->removedAttributes;
    }

    public function addRemovedAttribute(SampleInstanceTemplateRemovedAttribute $attribute)
    {
        if (!$this->getRemovedAttributes()->contains($attribute)) {
            $this->getRemovedAttributes()->add($attribute);
            $attribute->setParent($this);
        }

        return $this;
    }

    public function removeRemovedAttribute(SampleInstanceTemplateRemovedAttribute $attribute)
    {
        $this->getRemovedAttributes()->removeElement($attribute);

        return $this;
    }
}
