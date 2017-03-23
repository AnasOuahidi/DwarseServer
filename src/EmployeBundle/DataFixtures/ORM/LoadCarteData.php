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
    private $encoder;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
        $this->encoder = $this->container->get('security.password_encoder');
    }

    private function addCarte($numero, $employe, $categorie) {
        $carte = new Carte();
        $carte->setNumero($numero);
        $carte->setSolde($categorie->getCredit());
        $carte->setOpposed(false);
        $carte->setPassword($this->encoder->encodePassword($carte, "0000"));
        $carte->setCategorie($categorie);
        $carte->setEmploye($employe);
        $employe->setCarte($carte);
        return $carte;
    }

    public function load(ObjectManager $manager) {
        $responsable = $this->getReference('categorieResponsable');
        $cadre = $this->getReference('categorieCadre');
        $stagiaire = $this->getReference('categorieStagiaire');
        $aouahidi = $this->getReference('aouahidi');
        $jgadomski = $this->getReference('jgadomski');
        $nbengamra = $this->getReference('nbengamra');
        $abenmiled = $this->getReference('abenmiled');
        $carteAouahidi = $this->addCarte('0x4 0x84 0x2D 0x4A 0x63 0x43 0x80', $aouahidi, $responsable);
        $carteJgadomski = $this->addCarte('0x4 0x1A 0x27 0x4A 0x73 0x43 0x80', $jgadomski, $responsable);
        $carteNbengamra = $this->addCarte('0x4 0x47 0x28 0x4A 0x73 0x43 0x80', $nbengamra, $cadre);
        $carteAbenmiled = $this->addCarte('0x4 0x8D 0x2D 0x4A 0x63 0x43 0x80', $abenmiled, $stagiaire);
        $manager->persist($carteAouahidi);
        $manager->persist($carteJgadomski);
        $manager->persist($carteNbengamra);
        $manager->persist($carteAbenmiled);
        $manager->persist($aouahidi);
        $manager->persist($jgadomski);
        $manager->persist($nbengamra);
        $manager->persist($abenmiled);
        $manager->flush();
        $this->addReference('carteAouahidi', $carteAouahidi);
        $this->addReference('carteJgadomski', $carteJgadomski);
        $this->addReference('carteNbengamra', $carteNbengamra);
        $this->addReference('carteAbenmiled', $carteAbenmiled);
    }

    public function getOrder() {
        return 8;
    }
}