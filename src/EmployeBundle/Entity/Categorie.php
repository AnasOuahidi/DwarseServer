<?php

namespace EmployeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity(repositoryClass="EmployeBundle\Repository\CategorieRepository")
 */
class Categorie
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
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255, unique=true)
     */
    private $libelle;

    /**
     * @var float
     *
     * @ORM\Column(name="credit", type="float", unique=true)
     */
    private $credit;

    /**
     * @ORM\OneToMany(targetEntity="EmployeBundle\Entity\Carte", mappedBy="categorie")
     */
    private $cartes;

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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Categorie
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set credit
     *
     * @param float $credit
     *
     * @return Categorie
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get credit
     *
     * @return float
     */
    public function getCredit()
    {
        return $this->credit;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cartes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add carte
     *
     * @param \EmployeBundle\Entity\Carte $carte
     *
     * @return Categorie
     */
    public function addCarte(\EmployeBundle\Entity\Carte $carte)
    {
        $this->cartes[] = $carte;

        return $this;
    }

    /**
     * Remove carte
     *
     * @param \EmployeBundle\Entity\Carte $carte
     */
    public function removeCarte(\EmployeBundle\Entity\Carte $carte)
    {
        $this->cartes->removeElement($carte);
    }

    /**
     * Get cartes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCartes()
    {
        return $this->cartes;
    }
}
