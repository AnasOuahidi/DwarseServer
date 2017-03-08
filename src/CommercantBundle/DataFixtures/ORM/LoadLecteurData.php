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

    private function addLecteur($numero, $solde, $commercant) {
        $lecteur = new Lecteur();
        $lecteur->setNumero($numero);
        $lecteur->setSolde($solde);
        $lecteur->setCommercant($commercant);
        return $lecteur;
    }

    public function load(ObjectManager $manager) {
        $commercant = $this->getReference('commercant');
        $lecteur = $this->addLecteur(123456, 250, $commercant);
        $commercant->setLecteur($lecteur);
        $manager->persist($commercant);
        $manager->persist($lecteur);
        $manager->flush();
        $this->addReference('lecteur', $lecteur);
    }

    public function getOrder() {
        return 6;
    }
}