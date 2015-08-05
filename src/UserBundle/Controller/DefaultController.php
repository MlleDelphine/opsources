<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Request;

class DefaultController extends Controller
{
    public function updateAction()
    {
        $this->container->get("ldap_user_service")->updateAll();
        die;
    }
}
