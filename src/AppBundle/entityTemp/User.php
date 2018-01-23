<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User extends BaseUser implements \FOS\MessageBundle\Model\ParticipantInterface {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=45, nullable=true)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=45, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="datenaiss", type="string", length=45, nullable=true)
     */
    private $datenaiss;

    /**
     * @var string
     *
     * @ORM\Column(name="lieunaiss", type="string", length=45, nullable=true)
     */
    private $lieunaiss;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=45, nullable=true)
     */
    private $telephone;


    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=45, nullable=true)
     */
    private $adresse;

    public function getId() {
        return $this->id;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return User
     */
    public function setPrenom($prenom) {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom() {
        return $this->prenom;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return User
     */
    public function setNom($nom) {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom() {
        return $this->nom;
    }

    /**
     * Set datenaiss
     *
     * @param string $datenaiss
     *
     * @return User
     */
    public function setDatenaiss($datenaiss) {
        $this->datenaiss = $datenaiss;

        return $this;
    }

    /**
     * Get datenaiss
     *
     * @return string
     */
    public function getDatenaiss() {
        return $this->datenaiss;
    }

    /**
     * Set lieunaiss
     *
     * @param string $lieunaiss
     *
     * @return User
     */
    public function setLieunaiss($lieunaiss) {
        $this->lieunaiss = $lieunaiss;

        return $this;
    }

    /**
     * Get lieunaiss
     *
     * @return string
     */
    public function getLieunaiss() {
        return $this->lieunaiss;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return User
     */
    public function setTelephone($telephone) {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone() {
        return $this->telephone;
    }


    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return User
     */
    public function setAdresse($adresse) {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse() {
        return $this->adresse;
    }

    public function __toString() {
        return $this->prenom . ' ' . $this->nom;
    }


}
