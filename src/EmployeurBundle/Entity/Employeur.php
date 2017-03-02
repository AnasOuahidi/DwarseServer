<?php

namespace EmployeurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Employeur
 *
 * @ORM\Table(name="employeur")
 * @ORM\Entity(repositoryClass="EmployeurBundle\Repository\EmployeurRepository")
 */
class Employeur {
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255, unique=true)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="siret", type="string", length=255, unique=true)
     */
    private $siret;

    /**
     * @var string
     *
     * @ORM\Column(name="numTel", type="string", length=255, unique=true)
     */
    private $numTel;

    /**
     * @var string
     *
     * @ORM\Column(name="civilite", type="string", length=255)
     */
    private $civilite;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, unique=true)
     */
    private $photo;

    /**
     * @ORM\OneToOne(targetEntity="AuthBundle\Entity\User")
     * @var User
     */
    private $user;


    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Employeur
     */
    public function setLibelle($libelle) {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle() {
        return $this->libelle;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Employeur
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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Employeur
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
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Employeur
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

    /**
     * Set siret
     *
     * @param string $siret
     *
     * @return Employeur
     */
    public function setSiret($siret) {
        $this->siret = $siret;

        return $this;
    }

    /**
     * Get siret
     *
     * @return string
     */
    public function getSiret() {
        return $this->siret;
    }

    /**
     * Set numTel
     *
     * @param string $numTel
     *
     * @return Employeur
     */
    public function setNumTel($numTel) {
        $this->numTel = $numTel;

        return $this;
    }

    /**
     * Get numTel
     *
     * @return string
     */
    public function getNumTel() {
        return $this->numTel;
    }

    /**
     * Set civilite
     *
     * @param string $civilite
     *
     * @return Employeur
     */
    public function setCivilite($civilite) {
        $this->civilite = $civilite;

        return $this;
    }

    /**
     * Get civilite
     *
     * @return string
     */
    public function getCivilite() {
        return $this->civilite;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Employeur
     */
    public function setPhoto($photo) {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto() {
        return $this->photo;
    }

    /**
     * Set user
     *
     * @param \AuthBundle\Entity\User $user
     *
     * @return Employeur
     */
    public function setUser(\AuthBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AuthBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
