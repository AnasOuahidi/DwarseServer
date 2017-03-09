<?php

namespace EmployeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddCreditCommand extends ContainerAwareCommand {
    protected function configure() {
        $this
            ->setName('add:credit')
            ->setDescription('Ajout du crédit au cartes selon la catégorie');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln('Début de la mise à jour du solde des cartes');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $cartes = $em->getRepository('EmployeBundle:Carte')->findAll();
        foreach ($cartes as $carte) {
            $carte->setSolde($carte->getSolde() + ($carte->getCategorie()->getCredit() / 30));
            $em->persist($carte);
        }
        $em->flush();
        $output->writeln('Fin de la mise à jour du solde des cartes');
    }
}
