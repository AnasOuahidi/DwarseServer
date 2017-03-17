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
        $resonsable = $this->addCategorie('Responsable', 360);
        $cadre = $this->addCategorie('Cadre', 270);
        $stagiaire = $this->addCategorie('Stagiaire', 180);
        $manager->persist($resonsable);
        $manager->persist($cadre);
        $manager->persist($stagiaire);
        $manager->flush();
        $this->addReference('categorieResponsable', $resonsable);
        $this->addReference('categorieCadre', $cadre);
        $this->addReference('categorieStagiaire', $stagiaire);
    }

    public function getOrder() {
        return 7;
    }
}