<?php
namespace EmployeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EmployeBundle\Entity\Carte;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadCarteData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface {
    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    private function addCarte($numero, $solde, $employe, $categorie) {
        $carte = new Carte();
        $carte->setNumero($numero);
        $carte->setSolde($solde);
        $carte->setOpposed(false);
        $carte->setPassword(1234);
        $carte->setCategorie($categorie);
        $carte->setEmploye($employe);
        return $carte;
    }

    public function load(ObjectManager $manager) {
        $responsable = $this->getReference('responsable');
        $cadre = $this->getReference('cadre');
        $stagiaire = $this->getReference('stagiaire');
        $employe = $this->getReference('employe');
        $employe1 = $this->getReference('employe1');
        $employe2 = $this->getReference('employe2');
        $employe3 = $this->getReference('employe3');
        $carte = $this->addCarte(123456, 250, $employe, $stagiaire);
        $carte1 = $this->addCarte(1234567, 250, $employe1, $cadre);
        $carte2 = $this->addCarte(12345678, 150, $employe2, $stagiaire);
        $carte3 = $this->addCarte(123456789, 350, $employe3, $responsable);
        $employe->setCarte($carte);
        $employe1->setCarte($carte1);
        $employe2->setCarte($carte2);
        $employe3->setCarte($carte3);
        $manager->persist($carte);
        $manager->persist($carte1);
        $manager->persist($carte2);
        $manager->persist($carte3);
        $manager->persist($employe);
        $manager->persist($employe1);
        $manager->persist($employe2);
        $manager->persist($employe3);
        $manager->flush();
        $this->addReference('carte', $carte);
        $this->addReference('carte1', $carte1);
        $this->addReference('carte2', $carte2);
        $this->addReference('carte3', $carte3);
    }

    public function getOrder() {
        return 8;
    }
}