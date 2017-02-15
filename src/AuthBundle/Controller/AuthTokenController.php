<?php

namespace AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AuthBundle\Entity\Credentials;
use AuthBundle\Form\CredentialsType;

class AuthTokenController extends Controller {
    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"user"})
     * @Rest\Post("/authtokens")
     */
    public function postAuthTokensAction(Request $request) {
        $credentials = new Credentials();
        $form = $this->createForm(CredentialsType::class, $credentials);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $form;
        }
        $em = $this->get('doctrine.orm.entity_manager');
        $user = $em->getRepository('AuthBundle:User')
            ->findOneByEmailOrLogin($credentials->getLogin());
        if (!$user) {
            return $this->invalidLogin();
        }
        $encoder = $this->get('security.password_encoder');
        $isPasswordValid = $encoder->isPasswordValid($user, $credentials->getPassword());
        if (!$isPasswordValid) {
            return $this->invalidPassword();
        }
        if (!$user->getConfirmed()) {
            return $this->invalidActivated();
        }
        return $user;
    }

    private function invalidLogin() {
        return View::create(['message' => 'Login incorrect'], Response::HTTP_BAD_REQUEST);
    }

    private function invalidPassword() {
        return View::create(['message' => 'Mot de passe incorrect'], Response::HTTP_BAD_REQUEST);
    }

    private function invalidActivated() {
        return View::create(['message' => 'Votre compte n\'est pas activé'], Response::HTTP_BAD_REQUEST);
    }
}
