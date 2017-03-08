<?php

namespace TransactionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use TransactionBundle\Entity\Transaction;
use TransactionBundle\Entity\VerifTransaction;
use TransactionBundle\Form\VerifTransactionType;

class TransactionController extends Controller {
    /**
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @Rest\Post("/paiement")
     */
    public function PaiementAction(Request $request) {
        $verifTransaction = new VerifTransaction();
        $form = $this->createForm(VerifTransactionType::class, $verifTransaction);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $form;
        }
        $em = $this->get('doctrine.orm.entity_manager');
        $carte = $em->getRepository("EmployeBundle:Carte")
            ->findOneByNumero($verifTransaction->getNumeroCarte());
        if ($carte == null) {
            return View::create(['message' => 'Carte introuvable'], Response::HTTP_BAD_REQUEST);
        }
        $lecteur =$em->getRepository("CommercantBundle:Lecteur")
            ->findOneByNumero($verifTransaction->getNumeroLecteur());
        if ($lecteur == null) {
            return View::create(['message' => 'Lecteur introuvable'], Response::HTTP_BAD_REQUEST);
        }
        $pin = $carte->getPassword();
        if ($pin != $verifTransaction->getPin()) {
            return View::create(['message' => 'Votre code pin est incorrecte'], Response::HTTP_BAD_REQUEST);
        }
        $opposed = $carte->getOpposed();
        if ($opposed) {
            return View::create(['message' => 'Votre carte est désactivée, contactez votre employeur'], Response::HTTP_BAD_REQUEST);
        }
        $solde = $carte->getSolde();
        if ($solde < $verifTransaction->getMontant() ) {
            return View::create(['message' => 'Votre solde est insuffisant'], Response::HTTP_BAD_REQUEST);
        }
        $transaction = new Transaction();
        $transaction->setDate(new \DateTime('now'));
        $transaction->setMontant($verifTransaction->getMontant());
        $transaction->setCarte($carte);
        $transaction->setLecteur($lecteur);
        $em->persist($transaction);

        $nouveauSolde= $solde - $verifTransaction->getMontant();
        $carte->setSolde($nouveauSolde);
        $em->persist($carte);
        $em->flush();

        $message = \Swift_Message::newInstance();
        $logoImgUrl = $message->embed(\Swift_Image::fromPath('https://s3.amazonaws.com/dwarse/assets/img/logo.png'));
        $heartImgUrl = $message->embed(\Swift_Image::fromPath('https://s3.amazonaws.com/dwarse/assets/img/heart.png'));
        $name = $lecteur->getCommercant()->getCivilite(). ' ' .$lecteur->getCommercant()->getNom(). ' ' . $lecteur->getCommercant()->getPrenom();
        $message->setSubject('Nouvelle transaction Aventix')
            ->setFrom(array('dwarse.development@gmail.com' => 'Dwarse Team'))
            ->setTo($lecteur->getCommercant()->getUser()->getEmail())
            ->setCharset('utf-8')
            ->setContentType('text/html')
            ->setBody($this->renderView('@Transaction/Emails/post_transaction.html.twig',
                [
                    'logoImgUrl' => $logoImgUrl,
                    'heartImgUrl' => $heartImgUrl,
                    'name' => $name,
                    'date' => $transaction->getDate()->format('dd/mm/YYYY'),
                    'heure' => $transaction->getDate()->format('H:i'),
                    'montant' => $transaction->getMontant()
                ]
            ));
        $this->get('mailer')->send($message);

        return ["Success" => "La transaction à bien été effectuée"];
    }

}
