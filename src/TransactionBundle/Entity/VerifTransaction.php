<?php

namespace TransactionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


class VerifTransaction {

    private $numeroCarte;

    private $montant;

    private $pin;

    public function setNumeroCarte($numeroCarte) {
        $this->numeroCarte = $numeroCarte;

        return $this;
    }

    public function getNumeroCarte() {
        return $this->numeroCarte;
    }

    public function setMontant($montant) {
        $this->montant = $montant;

        return $this;
    }

    public function getMontant() {
        return $this->montant;
    }

    public function setPin($pin) {
        $this->pin = $pin;

        return $this;
    }

    public function getPin() {
        return $this->pin;
    }
}

