<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function updateAction()
    {
        $membersUpdated = $this->container->get("ldap_user_service")->updateAll();
        return new Response(count($membersUpdated));
    }

    public function updateOneAction($login)
    {
        $this->container->get("ldap_user_service")->updateByLogin($login);
        return new Response();
    }
}
