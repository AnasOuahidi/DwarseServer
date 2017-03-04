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
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"user"})
     * @Rest\Post("/profile")
     */
    public function postEmployeurAction(Request $request) {
        $employeur = new Employeur();
        $form = $this->createForm(EmployeurType::class, $employeur);
        $form->submit($request->request->get('profile'));
        if (!$form->isValid()) {
            return $form;
        }
        $token = $request->query->get('token');
        $em = $this->get('doctrine.orm.entity_manager');
        $authToken = $em->getRepository('AuthBundle:AuthToken')->findOneByValue($token);
        $user = $authToken->getUser();
        if ($user->getRole() != 'ROLE_EMPLOYEUR') {
            return View::create(['message' => 'Vous êtes pas un employeur'], Response::HTTP_BAD_REQUEST);
        }
        $user->setEmployeur($employeur);
        $employeur->setUser($user);
        $file = $request->files->get('file');
        $extention = $file->getClientOriginalExtension();
        $libelle = $user->getLogin() . '.' . $extention;
        $root = str_replace('app', '', $this->get('kernel')->getRootDir());
        $directory = $root . 'web' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'employeur';
        $photo = $directory . DIRECTORY_SEPARATOR . $libelle;
        $employeur->setPhoto($photo);
        $em->persist($employeur);
        $em->persist($user);
        $em->flush();
        if (is_object($file) && $file->isValid()) {
            $file->move($directory, $libelle);
        }
        return $user;
    }
}
