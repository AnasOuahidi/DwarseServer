<?php

namespace EmployeurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;


class OppositionController extends Controller {
    /**
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @Rest\Post("/opposition")
     */
    public function OppositionAction(Request $request) {
        $id = $request->request->get("id");
        $em = $this->get('doctrine.orm.entity_manager');
        $employe = $em->getRepository("EmployeBundle:Employe")->find($id);
        if ($employe == null) {
            return View::create(['message' => 'Employé introuvable'], Response::HTTP_BAD_REQUEST);
        }
        $carte = $employe->getCarte();
        $carte->setOpposed(true);
        $em->persist($carte);
        $em->flush();

        return ["Success" => "L'opposition à bien été effectuée"];
    }

}
