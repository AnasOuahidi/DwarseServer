<?php
namespace TransactionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TransactionBundle\Entity\Transaction;

class LoadTransactionData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface {
    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    private function addTransaction($montant, $carte, $lecteur) {
        $transaction = new Transaction();
        $transaction->setDate(new \DateTime());
        $transaction->setMontant($montant);
        $transaction->setCarte($carte);
        $transaction->setLecteur($lecteur);
        return $transaction;
    }

    public function load(ObjectManager $manager) {
        $carte = $this->getReference('carte');
        $carte1 = $this->getReference('carte1');
        $carte2 = $this->getReference('carte2');
        $carte3 = $this->getReference('carte3');
        $lecteur = $this->getReference('lecteur');
        $transaction1 = $this->addTransaction(15, $carte, $lecteur);
        $transaction2 = $this->addTransaction(25, $carte, $lecteur);
        $transaction3 = $this->addTransaction(20, $carte, $lecteur);
        $transaction4 = $this->addTransaction(22, $carte, $lecteur);
        $transaction5 = $this->addTransaction(16, $carte1, $lecteur);
        $transaction6 = $this->addTransaction(9, $carte1, $lecteur);
        $transaction7 = $this->addTransaction(17, $carte1, $lecteur);
        $transaction8 = $this->addTransaction(12, $carte1, $lecteur);
        $transaction9 = $this->addTransaction(13, $carte2, $lecteur);
        $transaction10 = $this->addTransaction(18, $carte2, $lecteur);
        $transaction11 = $this->addTransaction(15, $carte2, $lecteur);
        $transaction12 = $this->addTransaction(14, $carte2, $lecteur);
        $transaction13 = $this->addTransaction(6, $carte3, $lecteur);
        $transaction14 = $this->addTransaction(8, $carte3, $lecteur);
        $transaction15 = $this->addTransaction(9, $carte3, $lecteur);
        $transaction16 = $this->addTransaction(16, $carte3, $lecteur);
        $manager->persist($transaction1);
        $manager->persist($transaction2);
        $manager->persist($transaction3);
        $manager->persist($transaction4);
        $manager->persist($transaction5);
        $manager->persist($transaction6);
        $manager->persist($transaction7);
        $manager->persist($transaction8);
        $manager->persist($transaction9);
        $manager->persist($transaction10);
        $manager->persist($transaction11);
        $manager->persist($transaction12);
        $manager->persist($transaction13);
        $manager->persist($transaction14);
        $manager->persist($transaction15);
        $manager->persist($transaction16);
        $manager->flush();
    }

    public function getOrder() {
        return 9;
    }
}