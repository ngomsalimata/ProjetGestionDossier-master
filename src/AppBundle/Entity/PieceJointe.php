<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PieceJointe
 *
 * @ORM\Table(name="piece_jointe", indexes={@ORM\Index(name="fk_piece_jointe_dossier1", columns={"dossier_id"}), @ORM\Index(name="user", columns={"user"})})
 * @ORM\Entity
 */
class PieceJointe
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
     * @ORM\Column(name="libelle", type="string", length=145, nullable=false)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="fichier", type="string", length=145, nullable=true)
     */
    private $fichier;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_association", type="datetime", nullable=true)
     */
    private $dateAssociation;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Dossier
     *
     * @ORM\ManyToOne(targetEntity="Dossier")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="dossier_id", referencedColumnName="id")
     * })
     */
    private $dossier;



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
     * Set libelle
     *
     * @param string $libelle
     * @return PieceJointe
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return PieceJointe
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set fichier
     *
     * @param string $fichier
     * @return PieceJointe
     */
    public function setFichier($fichier)
    {
        $this->fichier = $fichier;

        return $this;
    }

    /**
     * Get fichier
     *
     * @return string 
     */
    public function getFichier()
    {
        return $this->fichier;
    }

    /**
     * Set dateAssociation
     *
     * @param \DateTime $dateAssociation
     * @return PieceJointe
     */
    public function setDateAssociation($dateAssociation)
    {
        $this->dateAssociation = $dateAssociation;

        return $this;
    }

    /**
     * Get dateAssociation
     *
     * @return \DateTime 
     */
    public function getDateAssociation()
    {
        return $this->dateAssociation;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return PieceJointe
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
     * Set dossier
     *
     * @param \AppBundle\Entity\Dossier $dossier
     * @return PieceJointe
     */
    public function setDossier(\AppBundle\Entity\Dossier $dossier = null)
    {
        $this->dossier = $dossier;

        return $this;
    }

    /**
     * Get dossier
     *
     * @return \AppBundle\Entity\Dossier 
     */
    public function getDossier()
    {
        return $this->dossier;
    }
    
    public function __toString() {
        return $this->libelle ;
    }
}
