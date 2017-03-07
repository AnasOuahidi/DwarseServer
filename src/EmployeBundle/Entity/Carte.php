<?php

namespace EmployeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Carte
 *
 * @ORM\Table(name="carte")
 * @ORM\Entity(repositoryClass="EmployeBundle\Repository\CarteRepository")
 */
class Carte
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
     * @ORM\Column(name="password", type="integer")
     */
    private $password;

    /**
     * @ORM\OneToOne(targetEntity="EmployeBundle\Entity\Employe")
     * @var Employe
     */
    private $employe;

    /**
     * @ORM\ManyToOne(targetEntity="EmployeBundle\Entity\Categorie", inversedBy="carte")
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return Carte
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
     * @param float $solde
     *
     * @return Carte
     */
    public function setSolde($solde)
    {
        $this->solde = $solde;

        return $this;
    }

    /**
     * Get solde
     *
     * @return float
     */
    public function getSolde()
    {
        return $this->solde;
    }

    /**
     * Set opposed
     *
     * @param boolean $opposed
     *
     * @return Carte
     */
    public function setOpposed($opposed)
    {
        $this->opposed = $opposed;

        return $this;
    }

    /**
     * Get opposed
     *
     * @return bool
     */
    public function getOpposed()
    {
        return $this->opposed;
    }

    /**
     * Set password
     *
     * @param integer $password
     *
     * @return Carte
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return int
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set employe
     *
     * @param \EmployeBundle\Entity\Employe $employe
     *
     * @return Carte
     */
    public function setEmploye(\EmployeBundle\Entity\Employe $employe = null)
    {
        $this->employe = $employe;

        return $this;
    }

    /**
     * Get employe
     *
     * @return \EmployeBundle\Entity\Employe
     */
    public function getEmploye()
    {
        return $this->employe;
    }

    /**
     * Set categorie
     *
     * @param \EmployeBundle\Entity\Categorie $categorie
     *
     * @return Carte
     */
    public function setCategorie(\EmployeBundle\Entity\Categorie $categorie = null)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \EmployeBundle\Entity\Categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
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
     * @return Carte
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
