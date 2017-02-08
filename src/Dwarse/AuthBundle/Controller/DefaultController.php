<?php

namespace Dwarse\AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DwarseAuthBundle:Default:index.html.twig');
    }
}
