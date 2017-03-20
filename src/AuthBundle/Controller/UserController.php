<?php
namespace AuthBundle\Controller;

use AuthBundle\Entity\User;
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
            $user->setConfirmationToken($this->generateToken(64));
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($user);
            $url = 'https://dwarse.github.io/#!/confirm/' . $user->getConfirmationToken();
            $message = \Swift_Message::newInstance();
            $logoImgUrl = $message->embed(\Swift_Image::fromPath('https://s3.amazonaws.com/dwarse/assets/img/logo.png'));
            $heartImgUrl = $message->embed(\Swift_Image::fromPath('https://s3.amazonaws.com/dwarse/assets/img/heart.png'));
            $message->setSubject('Confirmation de compte')
                ->setFrom(array('dwarse.development@gmail.com' => 'Dwarse Team'))->setTo($user->getEmail())
                ->setCharset('utf-8')->setContentType('text/html')
                ->setBody($this->renderView('AuthBundle:Emails:post_auth_tokens.html.twig', ['login' => $user->getLogin(),
                    'url' => $url, 'logoImgUrl' => $logoImgUrl, 'heartImgUrl' => $heartImgUrl]));
            $this->get('mailer')->send($message);
            $em->flush();
            return $user;
        }
        else {
            return $form;
        }
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
