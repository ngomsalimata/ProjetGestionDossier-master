<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FosGroup
 *
 * @ORM\Table(name="fos_group", uniqueConstraints={@ORM\UniqueConstraint(name="codegroupe", columns={"codegroupe"})})
 * @ORM\Entity
 */
 
class FosGroup
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="codegroupe", type="string", length=45, nullable=true)
     */
    private $codegroupe;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array", nullable=false)
     */
    private $roles;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="group")
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set codegroupe
     *
     * @param string $codegroupe
     *
     * @return FosGroup
     */
    public function setCodegroupe($codegroupe)
    {
        $this->codegroupe = $codegroupe;

        return $this;
    }

    /**
     * Get codegroupe
     *
     * @return string
     */
    public function getCodegroupe()
    {
        return $this->codegroupe;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return FosGroup
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set roles
     *
     * @param array $roles
     *
     * @return FosGroup
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Add user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return FosGroup
     */
    public function addUser(\AppBundle\Entity\User $user)
    {
        $this->user[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \AppBundle\Entity\User $user
     */
    public function removeUser(\AppBundle\Entity\User $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUser()
    {
        return $this->user;
    }
	
	public function __toString() 
    {
        return $this->name;
    }
}
