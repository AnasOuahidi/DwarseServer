<?php
namespace AuthBundle\DataFixtures\ORM;

use AuthBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EmployeBundle\Entity\Employe;
use EmployeurBundle\Entity\Employeur;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadEmployeData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface {
    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    private function addEmploye($nom, $civilite, $photo, $numTel, $user, $employeur) {
        $employe = new Employe();
        $employe->setNom($nom);
        $employe->setPrenom($nom);
        $employe->setCivilite($civilite);
        $employe->setPhoto($photo);
        $employe->setNumTel($numTel);
        $employe->setDateNaissance(new \DateTime());
        $employe->setEmployeur($employeur);
        $employe->setUser($user);
        return $employe;
    }

    public function employeToemployeur($manager, $user, $employeur, $employe){
        $user->setEmploye($employe);
        $employe->setUser($user);
        $employe->setEmployeur($employeur);
        $employeur->addEmploye($employe);
        $manager->persist($employe);
        $manager->persist($employeur);
        $manager->persist($user);
    }

    public function load(ObjectManager $manager) {
        $employeur = $this->getReference('employeur');
        $user = $this->getReference('employeUser');
        $user1 = $this->getReference('employeUser1');
        $user2 = $this->getReference('employeUser2');
        $user3 = $this->getReference('employeUser3');
        $employe = $this->addEmploye('employe', 'Mr.', 'https://s3.amazonaws.com/dwarse/employe/photo/employe.png'
            , '1234567890', $user, $employeur);
        $employe1 = $this->addEmploye('employe1', 'Mr.', 'https://s3.amazonaws.com/dwarse/employ/photo/employe1.png'
            , '1234567891', $user, $employeur);
        $employe2 = $this->addEmploye('employe2', 'Mr.', 'https://s3.amazonaws.com/dwarse/employe/photo/employe2.png'
            , '1234567892', $user, $employeur);
        $employe3 = $this->addEmploye('employe3', 'Mr.', 'https://s3.amazonaws.com/dwarse/employe/photo/employe3.png'
            , '1234567893', $user, $employeur);
        $this->employeToemployeur($manager, $user, $employeur, $employe);
        $this->employeToemployeur($manager, $user1, $employeur, $employe1);
        $this->employeToemployeur($manager, $user2, $employeur, $employe2);
        $this->employeToemployeur($manager, $user3, $employeur, $employe3);
        $manager->flush();
        $this->addReference('employe', $employe);
        $this->addReference('employe1', $employe1);
        $this->addReference('employe2', $employe2);
        $this->addReference('employe3', $employe3);
    }

    public function getOrder() {
        return 5;
    }
}