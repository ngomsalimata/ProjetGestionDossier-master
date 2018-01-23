<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserEntite
 *
 * @ORM\Table(name="user_entite", indexes={@ORM\Index(name="fk_User_has_entite_entite1", columns={"entite_id"}), @ORM\Index(name="fk_User_has_entite_User", columns={"User_id"})})
 * @ORM\Entity
 */
class UserEntite
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
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="User_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Entite
     *
     * @ORM\ManyToOne(targetEntity="Entite")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="entite_id", referencedColumnName="id")
     * })
     */
    private $entite;



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
     * @return UserEntite
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
     * Set entite
     *
     * @param \AppBundle\Entity\Entite $entite
     * @return UserEntite
     */
    public function setEntite(\AppBundle\Entity\Entite $entite = null)
    {
        $this->entite = $entite;

        return $this;
    }

    /**
     * Get entite
     *
     * @return \AppBundle\Entity\Entite 
     */
    public function getEntite()
    {
        return $this->entite;
    }
}
