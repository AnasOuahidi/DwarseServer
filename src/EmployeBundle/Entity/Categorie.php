<?php

namespace EmployeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use EmployeBundle\Entity\Carte;

/**
 * Categorie
 *
 * @ORM\Table(name="categories")
 * @ORM\Entity(repositoryClass="EmployeBundle\Repository\CategorieRepository")
 */
class Categorie {
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
    public function getId() {
        return $this->id;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Categorie
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
     * Set credit
     *
     * @param float $credit
     *
     * @return Categorie
     */
    public function setCredit($credit) {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get credit
     *
     * @return float
     */
    public function getCredit() {
        return $this->credit;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->cartes = new ArrayCollection();
    }

    /**
     * Add carte
     *
     * @param Carte $carte
     *
     * @return Categorie
     */
    public function addCarte(Carte $carte) {
        $this->cartes[] = $carte;

        return $this;
    }

    /**
     * Remove carte
     *
     * @param Carte $carte
     */
    public function removeCarte(Carte $carte) {
        $this->cartes->removeElement($carte);
    }

    /**
     * Get cartes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCartes() {
        return $this->cartes;
    }
}
