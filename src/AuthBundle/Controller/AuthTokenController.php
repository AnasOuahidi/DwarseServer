<?php

namespace AuthBundle\Controller;

use AuthBundle\Entity\AuthToken;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;

class AuthTokenController extends Controller {
    /**
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"user"})
     * @Rest\Post("/authtokens")
     */
    public function postAuthTokensAction(Request $request) {
        $token = $request->request->get('token');
        if (!$token) {
            return $this->invalidToken(false);
        }
        $em = $this->get('doctrine.orm.entity_manager');
        $user = $em->getRepository('AuthBundle:User')->findOneByConfirmationToken($token);
        if (!$user) {
            return $this->invalidToken(true);
        }
        if ($user->getConfirmed()) {
            $user->setConfirmationToken(null);
            $em->persist($user);
            $em->flush();
            return $this->alreadyConfirmed(true);
        }
        $user->setConfirmed(true);
        $user->setConfirmationToken(null);
        $authToken = new AuthToken();
        $authToken->setValue($this->generateToken(64));
        $authToken->setCreatedAt(new \DateTime('now'));
        $authToken->setUser($user);
        $user->setAuthToken($authToken);
        $em->persist($user);
        $em->persist($authToken);
        $em->flush();
        return $user;
    }

    private function invalidToken($exist) {
        if ($exist) {
            return View::create(['message' => 'Aucun utilisateur avec ce token'], Response::HTTP_BAD_REQUEST);
        } else {
            return View::create(['message' => 'Aucun token fourni'], Response::HTTP_BAD_REQUEST);
        }
    }

    private function alreadyConfirmed() {
        return View::create(['message' => 'Votre compte a déjà été confirmé'], Response::HTTP_BAD_REQUEST);
    }

    private function generateToken($length) {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $string = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }
        return $string;
    }
}
