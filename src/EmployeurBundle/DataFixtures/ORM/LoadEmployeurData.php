<?php
namespace AuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EmployeurBundle\Entity\Employeur;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadEmployeurData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface {
    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    private function addEmployeur($libelle, $civilite, $photo, $adresse, $siret, $numTel, $user) {
        $employeur = new Employeur();
        $employeur->setLibelle($libelle);
        $employeur->setNom($libelle);
        $employeur->setPrenom($libelle);
        $employeur->setCivilite($civilite);
        $employeur->setPhoto($photo);
        $employeur->setAdresse($adresse);
        $employeur->setSiret($siret);
        $employeur->setNumTel($numTel);
        $employeur->setUser($user);
        return $employeur;
    }

    public function load(ObjectManager $manager) {
        $user = $this->getReference('employeurUser');
        $employeur = $this->addEmployeur('employeur', 'Mr.', 'https://s3.amazonaws.com/dwarse/employeur/photo/employeur.png'
            , 'Test', '12345678912345', '1234567890', $user);
        $manager->persist($employeur);
        $user->setEmployeur($employeur);
        $manager->persist($user);
        $manager->flush();
        $this->addReference('employeur', $employeur);
    }

    public function getOrder() {
        return 3;
    }
}