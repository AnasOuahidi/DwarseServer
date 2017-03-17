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
        $aouahidi = $this->addUser('aouahidi', 'aouahidi@dwarse.fr', 'ROLE_EMPLOYE');
        $ygueddou = $this->addUser('ygueddou', 'ygueddou@dwarse.fr', 'ROLE_EMPLOYE');
        $jgadomski = $this->addUser('jgadomski', 'jgadomski@dwarse.fr', 'ROLE_EMPLOYE');
        $nbengamra = $this->addUser('nbengamra', 'nbengamra@dwarse.fr', 'ROLE_EMPLOYE');
        $abenmiled = $this->addUser('abenmiled', 'abenmiled@dwarse.fr', 'ROLE_EMPLOYE');
        $pdezarnaud = $this->addUser('pdezarnaud', 'pdezarnaud@dwarse.fr', 'ROLE_EMPLOYE');
        $employeur1 = $this->addUser('employeur1', $this->faker->companyEmail, 'ROLE_EMPLOYEUR');
        $employeur2 = $this->addUser('employeur2', $this->faker->companyEmail, 'ROLE_EMPLOYEUR');
        $employeur3 = $this->addUser('employeur3', $this->faker->companyEmail, 'ROLE_EMPLOYEUR');
        $commercant1 = $this->addUser('commercant1', $this->faker->companyEmail, 'ROLE_COMMERCANT');
        $commercant2 = $this->addUser('commercant2', $this->faker->companyEmail, 'ROLE_COMMERCANT');
        $commercant3 = $this->addUser('commercant3', $this->faker->companyEmail, 'ROLE_COMMERCANT');
        $manager->persist($aouahidi);
        $manager->persist($ygueddou);
        $manager->persist($jgadomski);
        $manager->persist($nbengamra);
        $manager->persist($abenmiled);
        $manager->persist($pdezarnaud);
        $manager->persist($employeur1);
        $manager->persist($employeur2);
        $manager->persist($employeur3);
        $manager->persist($commercant1);
        $manager->persist($commercant2);
        $manager->persist($commercant3);
        $manager->flush();
        $this->addReference('aouahidiUser', $aouahidi);
        $this->addReference('ygueddouUser', $ygueddou);
        $this->addReference('jgadomskiUser', $jgadomski);
        $this->addReference('nbengamraUser', $nbengamra);
        $this->addReference('abenmiledUser', $abenmiled);
        $this->addReference('pdezarnaudUser', $pdezarnaud);
        $this->addReference('employeur1User', $employeur1);
        $this->addReference('employeur2User', $employeur2);
        $this->addReference('employeur3User', $employeur3);
        $this->addReference('commercant1User', $commercant1);
        $this->addReference('commercant2User', $commercant2);
        $this->addReference('commercant3User', $commercant3);
    }

    public function getOrder() {
        return 1;
    }
}