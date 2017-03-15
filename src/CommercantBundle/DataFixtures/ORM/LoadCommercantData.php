<?php
namespace CommercantBundle\DataFixtures\ORM;

use CommercantBundle\Entity\Commercant;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadCommercantData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface {
    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    private function addCommercant($libelle, $civilite, $photo, $adresse, $siret, $iban, $numTel, $user) {
        $commercant = new Commercant();
        $commercant->setLibelle($libelle);
        $commercant->setNom($libelle);
        $commercant->setPrenom($libelle);
        $commercant->setCivilite($civilite);
        $commercant->setPhoto($photo);
        $commercant->setAdresse($adresse);
        $commercant->setSiret($siret);
        $commercant->setIban($iban);
        $commercant->setNumTel($numTel);
        $commercant->setUser($user);
        return $commercant;
    }

    public function load(ObjectManager $manager) {
        $user = $this->getReference('commercantUser');
        $commercant = $this->addCommercant('commercant', 'Mr.', 'https://s3.amazonaws.com/dwarse/commercant/photo/commercant.png', 'Test', '12345678912345', 'BE68539007547034', '1234567890', $user);
        $manager->persist($commercant);
        $user->setCommercant($commercant);
        $manager->persist($user);
        $manager->flush();
        $this->addReference('commercant', $commercant);
    }

    public function getOrder() {
        return 4;
    }
}