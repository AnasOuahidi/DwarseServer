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
        $carte->setSolde($categorie->getCredit() * 2);
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
        $pascale = $this->getReference('pascale');
        $youssef = $this->getReference('youssef');
        $mohammed = $this->getReference('mohammed');
        $florron = $this->getReference('florron');
        $carteAouahidi = $this->addCarte('0x4 0x84 0x2D 0x4A 0x63 0x43 0x80', $aouahidi, $responsable);
        $carteJgadomski = $this->addCarte('0x4 0x1A 0x27 0x4A 0x73 0x43 0x80', $jgadomski, $responsable);
        $carteNbengamra = $this->addCarte('0x4 0x47 0x28 0x4A 0x73 0x43 0x80', $nbengamra, $cadre);
        $carteAbenmiled = $this->addCarte('0x4 0x8D 0x2D 0x4A 0x63 0x43 0x80', $abenmiled, $stagiaire);
        $cartePascale = $this->addCarte('0x4 0x8C 0x2D 0x4T 0x63 0x43 0x80', $pascale, $responsable);
        $carteYoussef = $this->addCarte('0x4 0x8H 0x2D 0x4H 0x63 0x43 0x80', $youssef, $responsable);
        $carteMohammed = $this->addCarte('0x4 0x8D 0x2B 0x4A 0x65 0x43 0x80', $mohammed, $responsable);
        $carteFloront = $this->addCarte('0x4 0x8D 0x2H 0x4A 0x67 0x43 0x80', $florron, $responsable);
        $manager->persist($carteAouahidi);
        $manager->persist($carteJgadomski);
        $manager->persist($carteNbengamra);
        $manager->persist($carteAbenmiled);
        $manager->persist($cartePascale);
        $manager->persist($carteYoussef);
        $manager->persist($carteMohammed);
        $manager->persist($carteFloront);
        $manager->persist($aouahidi);
        $manager->persist($jgadomski);
        $manager->persist($nbengamra);
        $manager->persist($abenmiled);
        $manager->persist($pascale);
        $manager->persist($youssef);
        $manager->persist($mohammed);
        $manager->persist($florron);
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