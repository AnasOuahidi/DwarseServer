<?php

namespace EmployeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;


class ConsultationController extends Controller
{
    /**
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @Rest\Get("/solde")
     */
    public function ConsultationSoldesAction(Request $request)
    {
        $token = $request->query->get("token");
        $em = $this->get('doctrine.orm.entity_manager');
        $authToken = $em->getRepository('AuthBundle:AuthToken')->findOneByValue($token);
        $user = $authToken->getUser();
        $employe = $user->getEmploye();
        $carte = $employe->getCarte();
        if ($carte == null) {
            return View::create(['message' => 'Vous n\'avez pas de carte'], Response::HTTP_BAD_REQUEST);
        }
        $solde = $carte->getSolde();
        return ["solde" => $solde];
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @Rest\Get("/historique")
     */
    public function ConsultationHistoriquesAction()
    {

        return [];

    }

}
