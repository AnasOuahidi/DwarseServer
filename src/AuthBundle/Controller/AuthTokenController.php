<?php

namespace AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthTokenController extends Controller {
    /**
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"user"})
     * @Rest\Post("/authtokens")
     */
    public function postAuthTokensAction(Request $request) {
//        $user = $this->get('doctrine.orm.entity_manager')
//            ->getRepository('AuthBundle:AuthToken')
//            ->findOneByValue($request->headers->get('X-Auth-Token'))->getUser();
          // Confirmation du compte et création du auth token
//        $authToken = new AuthToken();
//        $authToken->setValue($this->generateToken(64));
//        $authToken->setCreatedAt(new \DateTime('now'));
//        $authToken->setUser($user);
//        $user->setAuthToken($authToken);
//        $em->persist($authToken);
    }
}
