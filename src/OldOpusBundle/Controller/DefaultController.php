<?php

namespace OldOpusBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/old", name="old")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager('old');

        $sheet = $em->getRepository("OldOpusBundle:OpusSheet")->find(209);
        $user = $em->getRepository("OldOpusBundle:OpusUsers")->find(295);

        return array('name' => 'Delphine', 'sheet' => $sheet, "user" => $user);
    }
}
