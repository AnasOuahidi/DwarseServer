<?php

namespace TransactionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transaction
 *
 * @ORM\Table(name="transaction")
 * @ORM\Entity(repositoryClass="TransactionBundle\Repository\TransactionRepository")
 */
class Transaction
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
     * @ORM\ManyToOne(targetEntity="EmployeBundle\Entity\Carte", inversedBy="transaction")
     * @ORM\JoinColumn(name="carte_id", referencedColumnName="id")
     */
    private $carte;

    /**
     * @ORM\ManyToOne(targetEntity="CommercantBundle\Entity\Lecteur", inversedBy="transaction")
     * @ORM\JoinColumn(name="lecteur_id", referencedColumnName="id")
     */
    private $lecteur;

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Transaction
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set montant
     *
     * @param float $montant
     *
     * @return Transaction
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return float
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set carte
     *
     * @param \EmployeBundle\Entity\Carte $carte
     *
     * @return Transaction
     */
    public function setCarte(\EmployeBundle\Entity\Carte $carte = null)
    {
        $this->carte = $carte;

        return $this;
    }

    /**
     * Get carte
     *
     * @return \EmployeBundle\Entity\Carte
     */
    public function getCarte()
    {
        return $this->carte;
    }

    /**
     * Set lecteur
     *
     * @param \CommercantBundle\Entity\Lecteur $lecteur
     *
     * @return Transaction
     */
    public function setLecteur(\CommercantBundle\Entity\Lecteur $lecteur = null)
    {
        $this->lecteur = $lecteur;

        return $this;
    }

    /**
     * Get lecteur
     *
     * @return \CommercantBundle\Entity\Lecteur
     */
    public function getLecteur()
    {
        return $this->lecteur;
    }
}
