<?php
namespace AuthBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Generator;
use Faker\Provider\fr_FR\Address;
use Faker\Provider\fr_FR\Company;
use Faker\Provider\fr_FR\Internet;
use Faker\Provider\fr_FR\Person;
use Faker\Provider\fr_FR\PhoneNumber;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AuthBundle\Entity\User;

class LoadUsersData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface {
    private $container;
    private $encoder;
    private $faker;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
        $this->encoder = $this->container->get('security.password_encoder');
        $this->faker = new Generator();
        $this->faker->addProvider(new Person($this->faker));
        $this->faker->addProvider(new Address($this->faker));
        $this->faker->addProvider(new PhoneNumber($this->faker));
        $this->faker->addProvider(new Company($this->faker));
        $this->faker->addProvider(new Internet($this->faker));
    }

    private function addUser($login, $email, $role) {
        $user = new User();
        $user->setLogin($login);
        $user->setEmail($email);
        $user->setPlainPassword($login);
        $encoded = $this->encoder->encodePassword($user, $login);
        $user->setPassword($encoded);
        $user->setRole($role);
        $user->setConfirmed(true);
        $user->setConfirmationToken(null);
        return $user;
    }

    public function load(ObjectManager $manager) {
        $aouahidi = $this->addUser('aouahidi', 'anas.ouahidi@insa-lyon.fr', 'ROLE_EMPLOYE');
        $jgadomski = $this->addUser('jgadomski', 'jenifer.gadomski@insa-lyon.fr', 'ROLE_EMPLOYE');
        $nbengamra = $this->addUser('nbengamra', 'nihel.ben-gamra@insa-lyon.fr', 'ROLE_EMPLOYE');
        $abenmiled = $this->addUser('abenmiled', 'aziz.ben-miled@insa-lyon.fr', 'ROLE_EMPLOYE');
        $ygueddou = $this->addUser('ygueddou', 'yasser.gueddou@insa-lyon.fr', 'ROLE_EMPLOYEUR');
        $pdezarnaud = $this->addUser('pdezarnaud', 'philippe.dezarnaud@insa-lyon.fr', 'ROLE_COMMERCANT');
        $pascale = $this->addUser('pascale', 'pascale.coquard@insa-lyon.fr', 'ROLE_EMPLOYE');
        $youssef = $this->addUser('youssef', 'youssef.amghar@insa-lyon.fr', 'ROLE_EMPLOYE');
        $mohammed = $this->addUser('mohammed', 'mohammed.ouhalima@insa-lyon.fr', 'ROLE_EMPLOYE');
        $florron = $this->addUser('floront', 'floront.duclos@insa-lyon.fr', 'ROLE_EMPLOYE');
        $manager->persist($aouahidi);
        $manager->persist($ygueddou);
        $manager->persist($jgadomski);
        $manager->persist($nbengamra);
        $manager->persist($abenmiled);
        $manager->persist($pdezarnaud);
        $manager->persist($pascale);
        $manager->persist($youssef);
        $manager->persist($mohammed);
        $manager->persist($florron);
        $manager->flush();
        $this->addReference('aouahidiUser', $aouahidi);
        $this->addReference('ygueddouUser', $ygueddou);
        $this->addReference('jgadomskiUser', $jgadomski);
        $this->addReference('nbengamraUser', $nbengamra);
        $this->addReference('abenmiledUser', $abenmiled);
        $this->addReference('pdezarnaudUser', $pdezarnaud);
        $this->addReference('pascaleUser', $pascale);
        $this->addReference('youssefUser', $youssef);
        $this->addReference('mohammedUser', $mohammed);
        $this->addReference('florronUser', $florron);
    }

    public function getOrder() {
        return 1;
    }
}