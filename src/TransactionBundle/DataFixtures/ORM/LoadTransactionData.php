<?php
namespace TransactionBundle\DataFixtures\ORM;

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
use TransactionBundle\Entity\Transaction;

class LoadTransactionData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface {
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

    private function addTransaction($montant, $carte, $lecteur) {
        $transaction = new Transaction();
        $transaction->setDate($this->faker->dateTimeBetween('-10 days', '-1 days'));
        $transaction->setMontant($montant);
        $transaction->setCarte($carte);
        $transaction->setAvenir(false);
        $transaction->setLecteur($lecteur);
        return $transaction;
    }

    public function load(ObjectManager $manager) {
        $carteAouahidi = $this->getReference('carteAouahidi');
//        $carteYgueddou = $this->getReference('carteYgueddou');
        $carteJgadomski = $this->getReference('carteJgadomski');
        $carteNbengamra = $this->getReference('carteNbengamra');
        $carteAbenmiled = $this->getReference('carteAbenmiled');
//        $cartePdezarnaud = $this->getReference('cartePdezarnaud');
        $lecteur = $this->getReference('lecteur');
//        $lecteur2 = $this->getReference('lecteur2');
//        $lecteur3 = $this->getReference('lecteur3');
//        $lecteurs = [$lecteur1, $lecteur2, $lecteur3];
        $cartes = [$carteAouahidi, $carteJgadomski, $carteNbengamra, $carteAbenmiled];
        for ($i = 0; $i < 100; $i++) {
            $montant = $this->random_float(5, 10);
            $carte = $cartes[rand(0, 3)];
//            $lecteur = $lecteurs[rand(0, 2)];
            if ($montant < $carte->getSolde() && !$carte->getOpposed()) {
                $transaction = $this->addTransaction($montant, $carte, $lecteur);
                $carte->setSolde($carte->getSolde() - $montant);
                $lecteur->setSolde($lecteur->getSolde() + $montant);
                $manager->persist($transaction);
                $manager->persist($carte);
                $manager->persist($lecteur);
            }
        }
        $manager->flush();
    }

    function random_float($min, $max) {
        return floatval(round(($min + lcg_value() * (abs($max - $min))), 2));
    }

    public function getOrder() {
        return 9;
    }
}