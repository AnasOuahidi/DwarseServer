<?php

namespace EmployeurBundle\Controller;

use EmployeurBundle\Entity\Employeur;
use EmployeurBundle\Form\EmployeurType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;

class ProfileController extends Controller {
    /**
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"employeur"})
     * @Rest\Post("/profile")
     */
    public function postEmployeurAction(Request $request) {
//        $file = $request->files->get('file');
        $employeur = new Employeur();
        $form = $this->createForm(EmployeurType::class, $employeur);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $form;
        }
        $token = $request->headers->get('X-Auth-Token');
        $em = $this->get('doctrine.orm.entity_manager');
        $authToken = $em->getRepository('AuthBundle:AuthToken')->findOneByValue($token);
        $user = $authToken->getUser();
        $user->setEmployeur($employeur);
        $employeur->setUser($user);
        $employeur->setPhoto("testing2");
        $em->persist($employeur);
        $em->persist($user);
        $em->flush();
        return $employeur;
    }
}
