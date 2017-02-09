<?php

namespace AuthBundle\Controller;

use AuthBundle\Entity\User;
use AuthBundle\Entity\AuthToken;
use AuthBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

class UserController extends Controller {
    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"user"})
     * @Rest\Post("/users")
     */
    public function postUsersAction(Request $request) {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['validation_groups' => ['Default', 'New']]);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $encoder = $this->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($encoded);
            $user->setConfirmed(false);
            $user->setConfirmationToken(base64_encode(random_bytes(64)));
            $authToken = new AuthToken();
            $authToken->setValue(base64_encode(random_bytes(64)));
            $authToken->setCreatedAt(new \DateTime('now'));
            $authToken->setUser($user);
            $user->setAuthToken($authToken);
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($user);
            $em->persist($authToken);
//            // avant ça on doit gerer l'envoi de mail :)
            $em->flush();
            return $user;
        } else {
            return $form;
        }
    }
}
