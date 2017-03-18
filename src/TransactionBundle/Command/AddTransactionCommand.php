<?php

namespace TransactionBundle\Command;

use TransactionBundle\Entity\Transaction;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddTransactionCommand extends ContainerAwareCommand {
    protected function configure() {
        $this->setName('add:transaction')->setDescription('Ajout de transaction qutidienement');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln('Ajout de transaction');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $cartes = $em->getRepository('EmployeBundle:Carte')->findAll();
        $lecteurs = $em->getRepository('CommercantBundle:Lecteur')->findAll();
        foreach ($cartes as $carte) {
            $montant1 = $this->random_float(5, 15);
            if ($montant1 < $carte->getSolde() && !$carte->getOpposed()) {
                $lecteur1 = $lecteurs[rand(0, 2)];
                $date1 = new \DateTime();
                $date1->modify('+3 hours');
                $transaction1 = new Transaction();
                $transaction1->setDate($date1);
                $transaction1->setMontant($montant1);
                $transaction1->setCarte($carte);
                $transaction1->setLecteur($lecteur1);
                $carte->setSolde($carte->getSolde() - $montant1);
                $em->persist($transaction1);
            }
            $montant2 = $this->random_float(5, 15);
            if ($montant2 < $carte->getSolde() && !$carte->getOpposed()) {
                $lecteur2 = $lecteurs[rand(0, 2)];
                $date2 = new \DateTime();
                $date2->modify('-3 hours');
                $transaction2 = new Transaction();
                $transaction2->setDate($date2);
                $transaction2->setMontant($montant2);
                $transaction2->setCarte($carte);
                $transaction2->setLecteur($lecteur2);
                $carte->setSolde($carte->getSolde() - $montant2);
                $em->persist($transaction2);
            }
        }
        $em->flush();
        $output->writeln('Fin d\'ajout de transaction');
    }

    function random_float($min, $max) {
        return floatval(round(($min + lcg_value() * (abs($max - $min))), 2));
    }

}
