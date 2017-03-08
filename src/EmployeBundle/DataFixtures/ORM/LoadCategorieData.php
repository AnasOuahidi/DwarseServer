<?php
namespace EmployeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EmployeBundle\Entity\Categorie;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadCategorieData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface {
    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    private function addCategorie($libelle, $credit) {
        $categorie = new Categorie();
        $categorie->setLibelle($libelle);
        $categorie->setCredit($credit);
        return $categorie;
    }

    public function load(ObjectManager $manager) {
        $resonsable = $this->addCategorie('Responsable', 350);
        $cadre = $this->addCategorie('Cadre', 250);
        $stagiaire = $this->addCategorie('Stagiaire', 150);
        $manager->persist($resonsable);
        $manager->persist($cadre);
        $manager->persist($stagiaire);
        $manager->flush();
        $this->addReference('responsable', $resonsable);
        $this->addReference('cadre', $cadre);
        $this->addReference('stagiaire', $stagiaire);
    }

    public function getOrder() {
        return 7;
    }
}