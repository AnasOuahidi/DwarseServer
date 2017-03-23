<?php
namespace EmployeurBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Generator;
use Faker\Provider\fr_FR\Address;
use Faker\Provider\DateTime;
use Faker\Provider\fr_FR\Company;
use Faker\Provider\fr_FR\Internet;
use Faker\Provider\fr_FR\Person;
use Faker\Provider\fr_FR\PhoneNumber;
use EmployeurBundle\Entity\Employeur;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadEmployeurData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface {
    private $container;
    private $faker;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
        $this->faker = new Generator();
        $this->faker->addProvider(new Person($this->faker));
        $this->faker->addProvider(new Address($this->faker));
        $this->faker->addProvider(new DateTime($this->faker));
        $this->faker->addProvider(new PhoneNumber($this->faker));
        $this->faker->addProvider(new Company($this->faker));
        $this->faker->addProvider(new Internet($this->faker));
    }

    private function addEmployeur($nom, $prenom, $civilite, $user) {
        $employeur = new Employeur();
        $employeur->setLibelle($this->faker->company);
        $employeur->setNom($nom);
        $employeur->setPrenom($prenom);
        $employeur->setCivilite($civilite);
        $employeur->setPhoto('https://s3.amazonaws.com/dwarse/employeur/photo/' . $user->getLogin() . '.png');
        $employeur->setAdresse($this->faker->address);
        $employeur->setSiret($this->faker->siret);
        $employeur->setNumTel($this->faker->e164PhoneNumber);
        $employeur->setUser($user);
        $user->setEmployeur($employeur);
        return $employeur;
    }

    public function load(ObjectManager $manager) {
        $ygueddouUser = $this->getReference('ygueddouUser');
        $ygueddou = $this->addEmployeur('Gueddou', 'Yasser', 'Mr.', $ygueddouUser);
        $manager->persist($ygueddouUser);
        $manager->persist($ygueddou);
        $manager->flush();
        $this->addReference('ygueddou', $ygueddou);
    }

    public function getOrder() {
        return 3;
    }
}