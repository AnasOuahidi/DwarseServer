<?php

namespace EmployeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use EmployeBundle\Entity\Categorie;
use EmployeBundle\Entity\Employe;
use Symfony\Component\Security\Core\User\UserInterface;
use TransactionBundle\Entity\Transaction;

/**
 * Carte
 *
 * @ORM\Table(name="cartes")
 * @ORM\Entity(repositoryClass="EmployeBundle\Repository\CarteRepository")
 */
class Carte implements UserInterface {
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="numero", type="string", length=255, unique=false)
     */
    private $numero;

    /**
     * @var float
     *
     * @ORM\Column(name="solde", type="float")
     */
    private $solde;

    /**
     * @var bool
     *
     * @ORM\Column(name="opposed", type="boolean")
     */
    private $opposed;

    /**
     * @var int
     *
     * @ORM\Column(name="password", type="string", length=255, unique=false)
     */
    private $password;

    /**
     * @ORM\OneToOne(targetEntity="EmployeBundle\Entity\Employe")
     * @var Employe
     */
    private $employe;

    /**
     * @ORM\ManyToOne(targetEntity="EmployeBundle\Entity\Categorie", inversedBy="cartes")
     * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id")
     */
    private $categorie;

    /**
     * @ORM\OnetoMany(targetEntity="TransactionBundle\Entity\Transaction", mappedBy="carte")
     */
    private $transactions;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return Carte
     */
    public function setNumero($numero) {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return int
     */
    public function getNumero() {
        return $this->numero;
    }

    /**
     * Set solde
     *
     * @param float $solde
     *
     * @return Carte
     */
    public function setSolde($solde) {
        $this->solde = $solde;

        return $this;
    }

    /**
     * Get solde
     *
     * @return float
     */
    public function getSolde() {
        return $this->solde;
    }

    /**
     * Set opposed
     *
     * @param boolean $opposed
     *
     * @return Carte
     */
    public function setOpposed($opposed) {
        $this->opposed = $opposed;

        return $this;
    }

    /**
     * Get opposed
     *
     * @return bool
     */
    public function getOpposed() {
        return $this->opposed;
    }

    /**
     * Set password
     *
     * @param integer $password
     *
     * @return Carte
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return int
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set employe
     *
     * @param Employe $employe
     *
     * @return Carte
     */
    public function setEmploye(Employe $employe = null) {
        $this->employe = $employe;

        return $this;
    }

    /**
     * Get employe
     *
     * @return Employe
     */
    public function getEmploye() {
        return $this->employe;
    }

    /**
     * Set categorie
     *
     * @param Categorie $categorie
     *
     * @return Carte
     */
    public function setCategorie(Categorie $categorie = null) {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return Categorie
     */
    public function getCategorie() {
        return $this->categorie;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->transactions = new ArrayCollection();
    }

    /**
     * Add transaction
     *
     * @param Transaction $transaction
     *
     * @return Carte
     */
    public function addTransaction(Transaction $transaction) {
        $this->transactions[] = $transaction;

        return $this;
    }

    /**
     * Remove transaction
     *
     * @param Transaction $transaction
     */
    public function removeTransaction(Transaction $transaction) {
        $this->transactions->removeElement($transaction);
    }

    /**
     * Get transactions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTransactions() {
        return $this->transactions;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles() {
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt() {
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername() {
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials() {
    }
}
