<?php

namespace Cscr\SlimsUserBundle\Entity;

use Cscr\SlimsApiBundle\ValueObject\ResearchGroup;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Class User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Cscr\SlimsUserBundle\Entity\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue("AUTO")
     *
     * @JMS\Exclude()
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=10, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var ResearchGroup
     *
     * @ORM\Column(name="research_group", type="string", length=255)
     */
    private $researchGroup;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
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
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }


    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->name,
            $this->isActive,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list($this->id, $this->name, $this->isActive) = unserialize($serialized);
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        // Credentials managed in Raven - nothing to do
    }

    /**
     * @param ResearchGroup $researchGroup
     *
     * @return User
     */
    public function setResearchGroup(ResearchGroup $researchGroup)
    {
        $this->researchGroup = $researchGroup->getName();
        return $this;
    }
}
