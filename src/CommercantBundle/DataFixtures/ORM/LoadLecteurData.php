<?php
namespace CommercantBundle\DataFixtures\ORM;

use CommercantBundle\Entity\Lecteur;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadLecteurData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface {
    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    private function addLecteur($numero, $commercant) {
        $lecteur = new Lecteur();
        $lecteur->setNumero($numero);
        $lecteur->setSolde(0);
        $lecteur->setCommercant($commercant);
        $commercant->setLecteur($lecteur);
        return $lecteur;
    }

    public function load(ObjectManager $manager) {
        $pdezarnaud = $this->getReference('pdezarnaud');
        $lecteur = $this->addLecteur($this->generateToken(8), $pdezarnaud);
        $manager->persist($pdezarnaud);
        $manager->persist($lecteur);
        $manager->flush();
        $this->addReference('lecteur', $lecteur);
    }

    public function getOrder() {
        return 6;
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