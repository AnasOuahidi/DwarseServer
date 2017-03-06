<?php

namespace EmployeurBundle\Entity;

class Compte {

    protected $login;

    protected $email;

    protected $categorie;

    public function setLogin($login) {
        $this->login = $login;

        return $this;
    }

    public function getLogin() {
        return $this->login;
    }

    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setCategorie($categorie) {
        $this->categorie = $categorie;

        return $this;
    }

    public function getCategorie() {
        return $this->categorie;
    }
}

