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
        $aouahidiUser = $this->getReference('aouahidiUser');
        $jgadomskiUser = $this->getReference('jgadomskiUser');
        $nbengamraUser = $this->getReference('nbengamraUser');
        $abenmiledUser = $this->getReference('abenmiledUser');
        $pascaleUser = $this->getReference('pascaleUser');
        $youssefUser = $this->getReference('youssefUser');
        $mohammedUser = $this->getReference('mohammedUser');
        $florronUser = $this->getReference('florronUser');
        $aouahidi = $this->addEmploye($manager, 'Ouahidi', 'Anas', 'Mr.', '2000-01-01', $aouahidiUser, $ygueddou);
        $jgadomski = $this->addEmploye($manager, 'Gadomski', 'Jenifer', 'Mlle.', '1999-01-01', $jgadomskiUser, $ygueddou);
        $nbengamra = $this->addEmploye($manager, 'Bengamra', 'Nihel', 'Mlle.', '1998-01-01', $nbengamraUser, $ygueddou);
        $abenmiled = $this->addEmploye($manager, 'Ben Miled', 'Aziz', 'Mr.', '1997-01-01', $abenmiledUser, $ygueddou);
        $pascale = $this->addEmploye($manager, 'Coquard', 'Pascale', 'Mme.', '1997-01-01', $pascaleUser, $ygueddou);
        $youssef = $this->addEmploye($manager, 'Amghar', 'Youssef', 'Mr.', '1997-01-01', $youssefUser, $ygueddou);
        $mohammed = $this->addEmploye($manager, 'OuHalima', 'Mohammed', 'Mr.', '1997-01-01', $mohammedUser, $ygueddou);
        $florron = $this->addEmploye($manager, 'Floront', 'Duclos', 'Mr.', '1997-01-01', $florronUser, $ygueddou);
        $manager->flush();
        $this->addReference('aouahidi', $aouahidi);
        $this->addReference('jgadomski', $jgadomski);
        $this->addReference('nbengamra', $nbengamra);
        $this->addReference('abenmiled', $abenmiled);
        $this->addReference('pascale', $pascale);
        $this->addReference('youssef', $youssef);
        $this->addReference('mohammed', $mohammed);
        $this->addReference('florron', $florron);
    }

    public function getOrder() {
        return 5;
    }
}