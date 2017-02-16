<?php

namespace AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImageController extends Controller {
    /**
     *
     * @Route("/heart", name="heart_image")
     */
    public function heartAction(Request $request) {
        $imageName = 'heart.png';
        $root = $request->server->get('DOCUMENT_ROOT');
        $file = $root . '/assets/img/' . $imageName;
        return new BinaryFileResponse($file);
    }

    /**
     *
     * @Route("/logo", name="logo_image")
     */
    public function logoAction(Request $request) {
        $imageName = 'logo.png';
        $root = $request->server->get('DOCUMENT_ROOT');
        $file = $root . '/assets/img/' . $imageName;
        return new BinaryFileResponse($file);
    }
}
