<?php

namespace CommercantBundle\Entity;

use AuthBundle\Entity\User;
use CommercantBundle\Entity\Lecteur;
use Doctrine\ORM\Mapping as ORM;

/**
 * Commercant
 *
 * @ORM\Entity(repositoryClass="CommercantBundle\Repository\CommercantRepository")
 * @ORM\Table(name="commercants",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="commercants_libelle_unique", columns={"libelle"})},
 *      uniqueConstraints={@ORM\UniqueConstraint(name="commercants_siret_unique", columns={"siret"})},
 *      uniqueConstraints={@ORM\UniqueConstraint(name="commercants_photo_unique", columns={"photo"})},
 *      uniqueConstraints={@ORM\UniqueConstraint(name="commercants_numTel_unique", columns={"numTel"})}
 * )
 */
class Commercant {
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
     * @ORM\Column(name="iban", type="string", length=255)
     */
    private $iban;

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
     * @ORM\OneToOne(targetEntity="CommercantBundle\Entity\Lecteur")
     * @var Lecteur
     */
    private $lecteur;

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
     * @return Commercant
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
     * @return Commercant
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
     * @return Commercant
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
     * @return Commercant
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
     * Set iban
     *
     * @param string $iban
     *
     * @return Commercant
     */
    public function setIban($iban) {
        $this->iban = $iban;

        return $this;
    }

    /**
     * Get iban
     *
     * @return string
     */
    public function getIban() {
        return $this->iban;
    }

    /**
     * Set siret
     *
     * @param string $siret
     *
     * @return Commercant
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
     * @return Commercant
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
     * @return Commercant
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
     * @return Commercant
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
     * @param User $user
     *
     * @return Commercant
     */
    public function setUser(User $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set lecteur
     *
     * @param Lecteur $lecteur
     *
     * @return Commercant
     */
    public function setLecteur(Lecteur $lecteur = null) {
        $this->lecteur = $lecteur;

        return $this;
    }

    /**
     * Get lecteur
     *
     * @return Lecteur
     */
    public function getLecteur() {
        return $this->lecteur;
    }
}
