<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dossier
 *
 * @ORM\Table(name="dossier")
 * @ORM\Entity
 */
class Dossier
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
     * @ORM\Column(name="libelle", type="string", length=190, nullable=true)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="degre_importance", type="string", length=45, nullable=true)
     */
    private $degreImportance;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut_traitement", type="datetime", nullable=true)
     */
    private $dateDebutTraitement;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin_traitement_prevu", type="datetime", nullable=true)
     */
    private $dateFinTraitementPrevu;

    /**
     * @var integer
     *
     * @ORM\Column(name="duree_maximum_traitement", type="integer", nullable=true)
     */
    private $dureeMaximumTraitement;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=45, nullable=true)
     */
    private $etat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_derniere_modification", type="datetime", nullable=true)
     */
    private $dateDerniereModification;

    /**
     * @var string
     *
     * @ORM\Column(name="utilisateur_derniere_modification", type="string", length=45, nullable=true)
     */
    private $utilisateurDerniereModification;



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
     * @return Dossier
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
     * Set degreImportance
     *
     * @param string $degreImportance
     * @return Dossier
     */
    public function setDegreImportance($degreImportance)
    {
        $this->degreImportance = $degreImportance;

        return $this;
    }

    /**
     * Get degreImportance
     *
     * @return string 
     */
    public function getDegreImportance()
    {
        return $this->degreImportance;
    }

    /**
     * Set dateDebutTraitement
     *
     * @param \DateTime $dateDebutTraitement
     * @return Dossier
     */
    public function setDateDebutTraitement($dateDebutTraitement)
    {
        $this->dateDebutTraitement = $dateDebutTraitement;

        return $this;
    }

    /**
     * Get dateDebutTraitement
     *
     * @return \DateTime 
     */
    public function getDateDebutTraitement()
    {
        return $this->dateDebutTraitement;
    }

    /**
     * Set dateFinTraitementPrevu
     *
     * @param \DateTime $dateFinTraitementPrevu
     * @return Dossier
     */
    public function setDateFinTraitementPrevu($dateFinTraitementPrevu)
    {
        $this->dateFinTraitementPrevu = $dateFinTraitementPrevu;

        return $this;
    }

    /**
     * Get dateFinTraitementPrevu
     *
     * @return \DateTime 
     */
    public function getDateFinTraitementPrevu()
    {
        return $this->dateFinTraitementPrevu;
    }

    /**
     * Set dureeMaximumTraitement
     *
     * @param integer $dureeMaximumTraitement
     * @return Dossier
     */
    public function setDureeMaximumTraitement($dureeMaximumTraitement)
    {
        $this->dureeMaximumTraitement = $dureeMaximumTraitement;

        return $this;
    }

    /**
     * Get dureeMaximumTraitement
     *
     * @return integer 
     */
    public function getDureeMaximumTraitement()
    {
        return $this->dureeMaximumTraitement;
    }

    /**
     * Set etat
     *
     * @param string $etat
     * @return Dossier
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return string 
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set dateDerniereModification
     *
     * @param \DateTime $dateDerniereModification
     * @return Dossier
     */
    public function setDateDerniereModification($dateDerniereModification)
    {
        $this->dateDerniereModification = $dateDerniereModification;

        return $this;
    }

    /**
     * Get dateDerniereModification
     *
     * @return \DateTime 
     */
    public function getDateDerniereModification()
    {
        return $this->dateDerniereModification;
    }

    /**
     * Set utilisateurDerniereModification
     *
     * @param string $utilisateurDerniereModification
     * @return Dossier
     */
    public function setUtilisateurDerniereModification($utilisateurDerniereModification)
    {
        $this->utilisateurDerniereModification = $utilisateurDerniereModification;

        return $this;
    }

    /**
     * Get utilisateurDerniereModification
     *
     * @return string 
     */
    public function getUtilisateurDerniereModification()
    {
        return $this->utilisateurDerniereModification;
    }
    
    public function __toString() {
        return $this->libelle;
    }
}
