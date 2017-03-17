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
        $commercant1 = $this->getReference('commercant1');
        $commercant2 = $this->getReference('commercant2');
        $commercant3 = $this->getReference('commercant3');
        $lecteur1 = $this->addLecteur($this->generateToken(8), $commercant1);
        $lecteur2 = $this->addLecteur($this->generateToken(8), $commercant2);
        $lecteur3 = $this->addLecteur($this->generateToken(8), $commercant3);
        $manager->persist($commercant1);
        $manager->persist($commercant2);
        $manager->persist($commercant3);
        $manager->persist($lecteur1);
        $manager->persist($lecteur2);
        $manager->persist($lecteur3);
        $manager->flush();
        $this->addReference('lecteur1', $lecteur1);
        $this->addReference('lecteur2', $lecteur2);
        $this->addReference('lecteur3', $lecteur3);
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