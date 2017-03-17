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

    private function addCommercant($user) {
        $commercant = new Commercant();
        $commercant->setLibelle($this->faker->company);
        $commercant->setNom($this->faker->lastName);
        $commercant->setPrenom($this->faker->firstName);
        $commercant->setCivilite($this->faker->title);
        $commercant->setPhoto('https://s3.amazonaws.com/dwarse/employeur/photo/' . $user->getLogin() . '.png');
        $commercant->setAdresse($this->faker->address);
        $commercant->setSiret($this->faker->siret);
        $commercant->setIban($this->faker->iban('fr_FR'));
        $commercant->setNumTel($this->faker->e164PhoneNumber);
        $commercant->setUser($user);
        $user->setCommercant($commercant);
        return $commercant;
    }

    public function load(ObjectManager $manager) {
        $commercant1User = $this->getReference('commercant1User');
        $commercant2User = $this->getReference('commercant2User');
        $commercant3User = $this->getReference('commercant3User');
        $commercant1 = $this->addCommercant($commercant1User);
        $commercant2 = $this->addCommercant($commercant2User);
        $commercant3 = $this->addCommercant($commercant3User);
        $manager->persist($commercant1);
        $manager->persist($commercant2);
        $manager->persist($commercant3);
        $manager->persist($commercant1User);
        $manager->persist($commercant2User);
        $manager->persist($commercant3User);
        $manager->flush();
        $this->addReference('commercant1', $commercant1);
        $this->addReference('commercant2', $commercant2);
        $this->addReference('commercant3', $commercant3);
    }

    public function getOrder() {
        return 4;
    }
}