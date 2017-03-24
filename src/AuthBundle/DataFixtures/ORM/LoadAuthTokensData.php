<?php
namespace AuthBundle\DataFixtures\ORM;

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
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AuthBundle\Entity\User;
use AuthBundle\Entity\AuthToken;

class LoadAuthTokensData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface {
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

    private function addAuthToken() {
        $authToken = new AuthToken();
        $authToken->setValue($this->generateToken(64));
        $authToken->setCreatedAt($this->faker->dateTimeBetween('-10 days', '+4 days'));
        return $authToken;
    }

    private function tokenToUser($manager, User $user, AuthToken $token) {
        $user->setAuthToken($token);
        $token->setUser($user);
        $manager->persist($token);
        $manager->merge($user);
    }

    public function load(ObjectManager $manager) {
        $this->tokenToUser($manager, $this->getReference('aouahidiUser'), $this->addAuthToken());
        $this->tokenToUser($manager, $this->getReference('ygueddouUser'), $this->addAuthToken());
        $this->tokenToUser($manager, $this->getReference('jgadomskiUser'), $this->addAuthToken());
        $this->tokenToUser($manager, $this->getReference('nbengamraUser'), $this->addAuthToken());
        $this->tokenToUser($manager, $this->getReference('abenmiledUser'), $this->addAuthToken());
        $this->tokenToUser($manager, $this->getReference('pdezarnaudUser'), $this->addAuthToken());
        $this->tokenToUser($manager, $this->getReference('pascaleUser'), $this->addAuthToken());
        $this->tokenToUser($manager, $this->getReference('youssefUser'), $this->addAuthToken());
        $this->tokenToUser($manager, $this->getReference('mohammedUser'), $this->addAuthToken());
        $this->tokenToUser($manager, $this->getReference('florronUser'), $this->addAuthToken());
        $manager->flush();
    }

    public function getOrder() {
        return 2;
    }

    private function generateToken($length) {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $string = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }
        return $string;
    }
}