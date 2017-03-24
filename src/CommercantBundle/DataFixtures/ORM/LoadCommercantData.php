<?php
namespace CommercantBundle\DataFixtures\ORM;

use CommercantBundle\Entity\Commercant;
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
use Faker\Provider\fr_FR\Payment;
use Faker\Provider\fr_FR\PhoneNumber;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadCommercantData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface {
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

    private function addCommercant($libelle, $nom, $prenom, $civilite, $user) {
        $commercant = new Commercant();
        $commercant->setLibelle($libelle);
        $commercant->setNom($nom);
        $commercant->setPrenom($prenom);
        $commercant->setCivilite($civilite);
        $commercant->setPhoto('https://s3.amazonaws.com/dwarse/commercant/photo/' . $user->getLogin() . '.png');
        $commercant->setAdresse($this->faker->address);
        $commercant->setSiret($this->faker->siret);
        $commercant->setIban($this->faker->iban('fr_FR'));
        $commercant->setNumTel($this->faker->e164PhoneNumber);
        $commercant->setUser($user);
        $user->setCommercant($commercant);
        return $commercant;
    }

    public function load(ObjectManager $manager) {
        $pdezarnaudUser = $this->getReference('pdezarnaudUser');
        $pdezarnaud = $this->addCommercant('Chez Philippes', 'Dezarnaud', 'Philippes', 'Mr.', $pdezarnaudUser);
        $manager->persist($pdezarnaud);
        $manager->persist($pdezarnaudUser);
        $manager->flush();
        $this->addReference('pdezarnaud', $pdezarnaud);
    }

    public function getOrder() {
        return 4;
    }
}