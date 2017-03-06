<?php

namespace EmployeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EmployeurBundle\Entity\Employeur;

/**
 * Employe
 *
 * @ORM\Entity(repositoryClass="EmployeBundle\Repository\EmployeRepository")
 * @ORM\Table(name="employes",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="employeurs_photo_unique", columns={"photo"})},
 *      uniqueConstraints={@ORM\UniqueConstraint(name="employes_numTel_unique", columns={"numTel"})}
 * )
 */
class Employe {
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
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateNaissance", type="datetime", length=255, nullable=true)
     */
    private $dateNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="numTel", type="string", length=255, unique=true, nullable=true)
     */
    private $numTel;

    /**
     * @var string
     *
     * @ORM\Column(name="civilite", type="string", length=255, nullable=true)
     */
    private $civilite;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, unique=true, nullable=true)
     */
    private $photo;

    /**
     * @ORM\OneToOne(targetEntity="AuthBundle\Entity\User")
     * @var User
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="EmployeurBundle\Entity\Employeur", inversedBy="employes")
     * @ORM\JoinColumn(name="employeur_id", referencedColumnName="id")
     */
    private $employeur;

    protected $password;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Employe
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
     * @return Employe
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
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     *
     * @return Employe
     */
    public function setDateNaissance($dateNaissance) {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance() {
        return $this->dateNaissance;
    }

    /**
     * Set numTel
     *
     * @param string $numTel
     *
     * @return Employe
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
     * @return Employe
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
     * @return Employe
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set user
     *
     * @param \AuthBundle\Entity\User $user
     *
     * @return Employe
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

    /**
     * Set employeur
     *
     * @param Employeur $employeur
     *
     * @return Employe
     */
    public function setEmployeur(Employeur $employeur = null)
    {
        $this->employeur = $employeur;

        return $this;
    }

    /**
     * Get employeur
     *
     * @return Employeur
     */
    public function getEmployeur()
    {
        return $this->employeur;
    }

    /**
     * @return mixed
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }
}
