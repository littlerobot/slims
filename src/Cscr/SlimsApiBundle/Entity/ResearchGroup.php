<?php

namespace Cscr\SlimsApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table(name="research_group")
 * @ORM\Entity(repositoryClass="Cscr\SlimsApiBundle\Entity\Repository\ResearchGroupRepository")
 */
class ResearchGroup
{
    /**
     * @var integer
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ResearchGroup
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}
