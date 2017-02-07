<?php

namespace DwarseAuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DwarseAuthBundle:Default:index.html.twig');
    }
}
