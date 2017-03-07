<?php

namespace CommercantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;

class ConsultationController extends Controller {
    /**
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @Rest\Get("/solde")
     */
    public function ConsultationSoldeAction(Request $request) {
        $token = $request->query->get("token");
        $em = $this->get('doctrine.orm.entity_manager');
        $authToken = $em->getRepository('AuthBundle:AuthToken')->findOneByValue($token);
        $user = $authToken->getUser();
        $commercant = $user->getCommercant();
        $lecteur = $commercant->getLecteur();
        if ($lecteur == null) {
            return View::create(['message' => 'Vous n\'avez pas de lecteur'], Response::HTTP_BAD_REQUEST);
        }
        $solde = $lecteur->getSolde();
        return ["solde" => $solde];
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @Rest\Get("/historique")
     */
    public function ConsultationHistoriqueAction() {
        return [];
    }

}
