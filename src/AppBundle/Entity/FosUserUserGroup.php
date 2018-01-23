<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FosUserUserGroup
 *
 * @ORM\Table(name="fos_user_user_group", uniqueConstraints={@ORM\UniqueConstraint(name="user_id", columns={"user_id", "group_id"})}, indexes={@ORM\Index(name="group_id", columns={"group_id"}), @ORM\Index(name="IDX_B3C77447A76ED395", columns={"user_id"})})
 * @ORM\Entity
 */
class FosUserUserGroup
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
     * @var \FosUser
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \FosGroup
     *
     * @ORM\ManyToOne(targetEntity="FosGroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     * })
     */
    private $group;



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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return FosUserUserGroup
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set group
     *
     * @param \AppBundle\Entity\FosGroup $group
     *
     * @return FosUserUserGroup
     */
    public function setGroup(\AppBundle\Entity\FosGroup $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \AppBundle\Entity\FosGroup
     */
    public function getGroup()
    {
        return $this->group;
    }
}
