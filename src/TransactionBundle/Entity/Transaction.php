<?php

namespace TransactionBundle\Entity;

use CommercantBundle\Entity\Lecteur;
use Doctrine\ORM\Mapping as ORM;
use EmployeBundle\Entity\Carte;

/**
 * Transaction
 *
 * @ORM\Table(name="transactions")
 * @ORM\Entity(repositoryClass="TransactionBundle\Repository\TransactionRepository")
 */
class Transaction {
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var float
     *
     * @ORM\Column(name="montant", type="float")
     */
    private $montant;

    /**
     * @ORM\ManyToOne(targetEntity="EmployeBundle\Entity\Carte", inversedBy="transactions")
     * @ORM\JoinColumn(name="carte_id", referencedColumnName="id")
     */
    private $carte;

    /**
     * @ORM\ManyToOne(targetEntity="CommercantBundle\Entity\Lecteur", inversedBy="transactions")
     * @ORM\JoinColumn(name="lecteur_id", referencedColumnName="id")
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Transaction
     */
    public function setDate($date) {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * Set montant
     *
     * @param float $montant
     *
     * @return Transaction
     */
    public function setMontant($montant) {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return float
     */
    public function getMontant() {
        return $this->montant;
    }

    /**
     * Set carte
     *
     * @param Carte $carte
     *
     * @return Transaction
     */
    public function setCarte(Carte $carte = null) {
        $this->carte = $carte;

        return $this;
    }

    /**
     * Get carte
     *
     * @return Carte
     */
    public function getCarte() {
        return $this->carte;
    }

    /**
     * Set lecteur
     *
     * @param Lecteur $lecteur
     *
     * @return Transaction
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
