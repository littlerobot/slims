<?php

namespace Cscr\SlimsUserBundle\Entity;

use Cscr\SlimsApiBundle\Entity\ResearchGroup;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 *
 * @ORM\Table(name="slims_user")
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
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var ResearchGroup
     *
     * @ORM\ManyToOne(targetEntity="Cscr\SlimsApiBundle\Entity\ResearchGroup")
     * @ORM\JoinColumn(name="research_group_id", referencedColumnName="id")
     */
    private $researchGroup;

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
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param boolean $active
     *
     * @return User
     */
    public function setIsActive($active)
    {
        $this->isActive = $active;

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
            $this->username,
            $this->name,
            $this->researchGroup,
            $this->isActive,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list($this->id, $this->username, $this->name, $this->researchGroup, $this->isActive) = unserialize($serialized);
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
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        // Credentials managed in Raven - nothing to do
    }

    /**
     * @return ResearchGroup
     */
    public function getResearchGroup()
    {
        return $this->researchGroup;
    }

    /**
     * @param  ResearchGroup $researchGroup
     * @return User
     */
    public function setResearchGroup(ResearchGroup $researchGroup)
    {
        $this->researchGroup = $researchGroup;

        return $this;
    }
}
