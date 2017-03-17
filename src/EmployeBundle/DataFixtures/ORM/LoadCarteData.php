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
        $ygueddou = $this->getReference('ygueddou');
        $jgadomski = $this->getReference('jgadomski');
        $nbengamra = $this->getReference('nbengamra');
        $abenmiled = $this->getReference('abenmiled');
        $pdezarnaud = $this->getReference('pdezarnaud');
        $carteAouahidi = $this->addCarte($this->generateToken(8), $aouahidi, $responsable);
        $carteYgueddou = $this->addCarte($this->generateToken(8), $ygueddou, $cadre);
        $carteJgadomski = $this->addCarte($this->generateToken(8), $jgadomski, $responsable);
        $carteNbengamra = $this->addCarte($this->generateToken(8), $nbengamra, $cadre);
        $carteAbenmiled = $this->addCarte($this->generateToken(8), $abenmiled, $stagiaire);
        $cartePdezarnaud = $this->addCarte($this->generateToken(8), $pdezarnaud, $stagiaire);
        $manager->persist($carteAouahidi);
        $manager->persist($carteYgueddou);
        $manager->persist($carteJgadomski);
        $manager->persist($carteNbengamra);
        $manager->persist($carteAbenmiled);
        $manager->persist($cartePdezarnaud);
        $manager->persist($aouahidi);
        $manager->persist($ygueddou);
        $manager->persist($jgadomski);
        $manager->persist($nbengamra);
        $manager->persist($abenmiled);
        $manager->persist($pdezarnaud);
        $manager->flush();
        $this->addReference('carteAouahidi', $carteAouahidi);
        $this->addReference('carteYgueddou', $carteYgueddou);
        $this->addReference('carteJgadomski', $carteJgadomski);
        $this->addReference('carteNbengamra', $carteNbengamra);
        $this->addReference('carteAbenmiled', $carteAbenmiled);
        $this->addReference('cartePdezarnaud', $cartePdezarnaud);
    }

    public function getOrder() {
        return 8;
    }

    private function generateToken($length) {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $string = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }
        return $string;
    }
}