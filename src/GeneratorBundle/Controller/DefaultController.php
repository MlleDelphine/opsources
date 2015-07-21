<?php

namespace GeneratorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('GeneratorBundle:Default:index.html.twig', array('name' => $name));
    }
}
