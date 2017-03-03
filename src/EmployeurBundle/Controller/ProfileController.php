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
        $employeur = new Employeur();
        $form = $this->createForm(EmployeurType::class, $employeur);
        $form->submit($request->request->get('profile'));
        if (!$form->isValid()) {
            return $form;
        }
//        $token = $request->request->get('token');
        $em = $this->get('doctrine.orm.entity_manager');
//        $authToken = $em->getRepository('AuthBundle:AuthToken')->findOneByValue($token);
//        $user = $authToken->getUser();
//        $user->setEmployeur($employeur);
//        $employeur->setUser($user);
        $em->persist($employeur);
        $file = $request->files->get('file');
        $extention = $file->getClientOriginalExtension();
        $libelle = $employeur->getId() . '.' . $extention;
        $directory = $this->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'employeur';
        $photo = $directory . DIRECTORY_SEPARATOR . $libelle;
        $employeur->setPhoto($photo);
        $em->persist($employeur);
        $em->persist($user);
        $em->flush();
        if (is_object($file) && $file->isValid()) {
            $file->move($directory, $libelle);
        }
        return $employeur;
    }
}
