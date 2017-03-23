<?php
namespace EmployeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EmployeBundle\Entity\Employe;
use Faker\Generator;
use Faker\Provider\fr_FR\Address;
use Faker\Provider\DateTime;
use Faker\Provider\fr_FR\Company;
use Faker\Provider\fr_FR\Internet;
use Faker\Provider\fr_FR\Person;
use Faker\Provider\fr_FR\Payment;
use Faker\Provider\fr_FR\PhoneNumber;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadEmployeData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface {
    private $container;
    private $faker;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
        $this->faker = new Generator();
        $this->faker->addProvider(new Person($this->faker));
        $this->faker->addProvider(new Address($this->faker));
        $this->faker->addProvider(new DateTime($this->faker));
        $this->faker->addProvider(new Payment($this->faker));
        $this->faker->addProvider(new PhoneNumber($this->faker));
        $this->faker->addProvider(new Company($this->faker));
        $this->faker->addProvider(new Internet($this->faker));
    }

    private function addEmploye($manager, $nom, $prenom, $civilite, $age, $user, $employeur) {
        $employe = new Employe();
        $employe->setNom($nom);
        $employe->setPrenom($prenom);
        $employe->setCivilite($civilite);
        $employe->setPhoto('https://s3.amazonaws.com/dwarse/employe/photo/' . $user->getLogin() . '.png');
        $employe->setNumTel($this->faker->e164PhoneNumber);
        $employe->setDateNaissance(new \DateTime($age));
        $employe->setEmployeur($employeur);
        $employe->setUser($user);
        $user->setEmploye($employe);
        $employeur->addEmploye($employe);
        $manager->persist($employe);
        $manager->persist($employeur);
        $manager->persist($user);
        return $employe;
    }

    public function load(ObjectManager $manager) {
        $ygueddou = $this->getReference('ygueddou');
//        $employeur1 = $this->getReference('employeur1');
//        $employeur2 = $this->getReference('employeur2');
//        $employeur3 = $this->getReference('employeur3');
        $aouahidiUser = $this->getReference('aouahidiUser');
//        $ygueddouUser = $this->getReference('ygueddouUser');
        $jgadomskiUser = $this->getReference('jgadomskiUser');
        $nbengamraUser = $this->getReference('nbengamraUser');
        $abenmiledUser = $this->getReference('abenmiledUser');
//        $pdezarnaudUser = $this->getReference('pdezarnaudUser');
        $aouahidi = $this->addEmploye($manager, 'Ouahidi', 'Anas', 'Mr.', '1996-01-01', $aouahidiUser, $ygueddou);
//        $ygueddou = $this->addEmploye($manager, 'Gueddou', 'Yasser', 'Mr.', $ygueddouUser, $ygueddou);
        $jgadomski = $this->addEmploye($manager, 'Gadomski', 'Jenifer', 'Mlle.', '1999-01-01', $jgadomskiUser, $ygueddou);
        $nbengamra = $this->addEmploye($manager, 'Bengamra', 'Nihel', 'Mlle.', '1998-01-01', $nbengamraUser, $ygueddou);
        $abenmiled = $this->addEmploye($manager, 'Ben Miled', 'Aziz', 'Mr.', '1997-01-01', $abenmiledUser, $ygueddou);
//        $pdezarnaud = $this->addEmploye($manager, 'Dezarnaud', 'Philippe', 'Mr.', $pdezarnaudUser, $ygueddou);
        $manager->flush();
        $this->addReference('aouahidi', $aouahidi);
//        $this->addReference('ygueddou', $ygueddou);
        $this->addReference('jgadomski', $jgadomski);
        $this->addReference('nbengamra', $nbengamra);
        $this->addReference('abenmiled', $abenmiled);
//        $this->addReference('pdezarnaud', $pdezarnaud);
    }

    public function getOrder() {
        return 5;
    }
}