<?php

namespace CommercantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lecteur
 *
 * @ORM\Table(name="lecteur",
 * uniqueConstraints={@ORM\UniqueConstraint(name="Lecteur_Numero_unique", columns={"numero"})})
 * @ORM\Entity(repositoryClass="CommercantBundle\Repository\LecteurRepository")
 */
class Lecteur
{
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
     * @ORM\Column(name="numero", type="integer", unique=true)
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return Lecteur
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return int
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set solde
     *
     * @param integer $solde
     *
     * @return Lecteur
     */
    public function setSolde($solde)
    {
        $this->solde = $solde;

        return $this;
    }

    /**
     * Get solde
     *
     * @return int
     */
    public function getSolde()
    {
        return $this->solde;
    }

    /**
     * Set commercant
     *
     * @param \CommercantBundle\Entity\Commercant $commercant
     *
     * @return Lecteur
     */
    public function setCommercant(\CommercantBundle\Entity\Commercant $commercant = null)
    {
        $this->commercant = $commercant;

        return $this;
    }

    /**
     * Get commercant
     *
     * @return \CommercantBundle\Entity\Commercant
     */
    public function getCommercant()
    {
        return $this->commercant;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->transactions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add transaction
     *
     * @param \TransactionBundle\Entity\Transaction $transaction
     *
     * @return Lecteur
     */
    public function addTransaction(\TransactionBundle\Entity\Transaction $transaction)
    {
        $this->transactions[] = $transaction;

        return $this;
    }

    /**
     * Remove transaction
     *
     * @param \TransactionBundle\Entity\Transaction $transaction
     */
    public function removeTransaction(\TransactionBundle\Entity\Transaction $transaction)
    {
        $this->transactions->removeElement($transaction);
    }

    /**
     * Get transactions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTransactions()
    {
        return $this->transactions;
    }
}
