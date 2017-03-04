<?php

namespace EmployeurBundle\Controller;

use EmployeurBundle\Entity\Employeur;
use EmployeurBundle\Form\EmployeurType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Aws\S3\S3Client;

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
        $file = $request->files->get('file');
        if (!is_object($file) || !$file->isValid()) {
            return View::create(['message' => 'Votre photo ne passe pas'], Response::HTTP_BAD_REQUEST);
        }
        $root = $this->get('kernel')->getRootDir();
        $keyContent = file_get_contents($root . '/AWS_ACCESS_KEY_ID.txt');
        $secretContent = file_get_contents($root . '/AWS_SECRET_ACCESS_KEY.txt');
        $bucket = getenv('S3_BUCKET_NAME')? getenv('S3_BUCKET_NAME') : 'dwarse';
        $key = getenv('AWS_ACCESS_KEY_ID')? getenv('AWS_ACCESS_KEY_ID') : $keyContent;
        $secret = getenv('AWS_SECRET_ACCESS_KEY')? getenv('AWS_SECRET_ACCESS_KEY') : $secretContent;
        $s3 = S3Client::factory(['key' => $key, 'secret' => $secret]);
        $extention = $file->getClientOriginalExtension();
        $libelle = 'employeur/photo/' . $user->getLogin() . '.' . $extention;
        $upload = $s3->upload($bucket, $libelle, fopen($_FILES['file']['tmp_name'], 'rb'), 'public-read');
        $photo = $upload->get('ObjectURL');
        $employeur->setPhoto($photo);
        $employeur->setUser($user);
        $user->setEmployeur($employeur);
        $em->persist($employeur);
        $em->persist($user);
        $em->flush();
        return $user;
    }
}
