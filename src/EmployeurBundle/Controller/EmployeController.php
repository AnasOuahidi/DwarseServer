<?php

namespace EmployeurBundle\Controller;

use AuthBundle\Entity\User;
use EmployeBundle\Entity\Employe;
use EmployeurBundle\Entity\Compte;
use EmployeurBundle\Form\CompteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;

class EmployeController extends Controller {
    /**
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"user"})
     * @Rest\Post("/employe")
     */
    public function postEmployeAction(Request $request) {
        return [];
//        $compte = new Compte();
//        $form = $this->createForm(CompteType::class, $compte);
//        $form->submit($request->request->all());
//        if (!$form->isValid()) {
//            return $form;
//        }
//        $token = $request->query->get('token');
//        $em = $this->get('doctrine.orm.entity_manager');
//        $authToken = $em->getRepository('AuthBundle:AuthToken')->findOneByValue($token);
//        $userEmployeur = $authToken->getUser();
//        $employeur = $userEmployeur->getEmployeur();
//        if ($employeur == null) {
//            return View::create(['message' => 'Créer d\'abord votre profile avant de penser à ajouter des employes!'], Response::HTTP_BAD_REQUEST);
//        }
//        if ($userEmployeur->getRole() != 'ROLE_EMPLOYEUR') {
//            return View::create(['message' => 'Vous n\'êtes pas un employeur'], Response::HTTP_BAD_REQUEST);
//        }
//        $user = new User();
//        $user->setEmail($compte->getEmail());
//        $user->setLogin($compte->getLogin());
//        $user->setRole('ROLE_EMPLOYE');
//        $user->setConfirmed(false);
//        $user->setConfirmationToken($this->generateToken(64));
//        $encoder = $this->get('security.password_encoder');
//        $password = $this->generateToken(8);
//        $user->setPlainPassword($password);
//        $encoded = $encoder->encodePassword($user, $password);
//        $user->setPassword($encoded);
//        $employe = new Employe();
//        $employe->setUser($user);
//        $employe->setEmployeur($employeur);
//        $employeur->addEmploye($employe);
//        $user->setEmploye($employe);
//        $em->persist($user);
//        $em->persist($employeur);
//        $em->persist($employe);
//        $url = 'https://dwarse.github.io/#!/confirm/' . $user->getConfirmationToken();
//        $message = \Swift_Message::newInstance();
//        $logoImgUrl = $message->embed(\Swift_Image::fromPath('https://s3.amazonaws.com/dwarse/assets/img/logo.png'));
//        $heartImgUrl = $message->embed(\Swift_Image::fromPath('https://s3.amazonaws.com/dwarse/assets/img/heart.png'));
//        $message->setSubject('Confirmation de compte')
//            ->setFrom(array('dwarse.development@gmail.com' => 'Dwarse Team'))
//            ->setTo($user->getEmail())
//            ->setCharset('utf-8')
//            ->setContentType('text/html')
//            ->setBody($this->renderView('EmployeurBundle:Emails:post_employe_tokens.html.twig',
//                [
//                    'loginEmploye' => $user->getLogin(),
//                    'loginEmployeur' => $userEmployeur->getLogin(),
//                    'password' => $password,
//                    'url' => $url,
//                    'logoImgUrl' => $logoImgUrl,
//                    'heartImgUrl' => $heartImgUrl
//                ]
//            ));
//        $this->get('mailer')->send($message);
//        $em->flush();
//        return $user;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"user"})
     * @Rest\Get("/employe")
     */
    public function getEmployeAction(Request $request) {
        $em = $this->get('doctrine.orm.entity_manager');
        $token = $request->query->get('token');
        $authToken = $em->getRepository('AuthBundle:AuthToken')->findOneByValue($token);
        $employeur = $authToken->getUser()->getEmployeur();
        $users = $em->getRepository('AuthBundle:User')->findAll();
        $employeUsers = [];
        foreach ($users as $user) {
            $employe = $user->getEmploye();
            if (isset($employe) && $employe->getEmployeur()->getId() === $employeur->getId()) {
                $employeUsers[] = $user;
            }
        }
        return $employeUsers;
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
