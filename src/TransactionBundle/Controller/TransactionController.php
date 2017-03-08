<?php

namespace TransactionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
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
            return View::create(['message' => 'Vous n\'êtes pas un employeur'], Response::HTTP_BAD_REQUEST);
        }
        $lecteur =$em->getRepository("CommercantBundle:Lecteur")
            ->findOneByNumero($verifTransaction->getNumeroLecteur());
        if ($lecteur == null) {
            return View::create(['message' => 'Vous n\'êtes pas un employeur'], Response::HTTP_BAD_REQUEST);
        }

        return [];
    }

}
