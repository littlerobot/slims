<?php

namespace Cscr\SlimsApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

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
     * @var ArrayCollection|SampleTypeAttribute[]
     *
     * @ORM\OneToMany(
     *  targetEntity="SampleTypeAttribute",
     *  mappedBy="parent",
     *  cascade={"PERSIST"}
     * )
     */
    private $attributes;

    public function __construct()
    {
        $this->attributes = new ArrayCollection();
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
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

    public function removeAttribute(SampleTypeAttribute $attribute)
    {
        $this->getAttributes()->removeElement($attribute);

        return $this;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }
}
