<?php

namespace CommercantBundle\Entity;

use CommercantBundle\Entity\Commercant;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use TransactionBundle\Entity\Transaction;

/**
 * Lecteur
 *
 * @ORM\Table(name="lecteurs",
 * uniqueConstraints={@ORM\UniqueConstraint(name="Lecteur_Numero_unique", columns={"numero"})})
 * @ORM\Entity(repositoryClass="CommercantBundle\Repository\LecteurRepository")
 */
class Lecteur {
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
     * @var int
     *
     * @ORM\Column(name="solde", type="float")
     */
    private $solde;

    /**
     * @ORM\OneToOne(targetEntity="CommercantBundle\Entity\Commercant")
     * @var Commercant
     */
    private $commercant;

    /**
     * @ORM\OnetoMany(targetEntity="TransactionBundle\Entity\Transaction", mappedBy="lecteur")
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
     * @return Lecteur
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
     * @param integer $solde
     *
     * @return Lecteur
     */
    public function setSolde($solde) {
        $this->solde = $solde;

        return $this;
    }

    /**
     * Get solde
     *
     * @return int
     */
    public function getSolde() {
        return $this->solde;
    }

    /**
     * Set commercant
     *
     * @param Commercant $commercant
     *
     * @return Lecteur
     */
    public function setCommercant(Commercant $commercant = null) {
        $this->commercant = $commercant;

        return $this;
    }

    /**
     * Get commercant
     *
     * @return Commercant
     */
    public function getCommercant() {
        return $this->commercant;
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
     * @return Lecteur
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
}
