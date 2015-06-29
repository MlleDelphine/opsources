<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 22/06/2015
 * Time: 16:56
 */

namespace FormGeneratorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Service;



class DefaultController extends Controller{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $user = $this->getUser();
        return $this->render('AppBundle:Default:index.html.twig', array('user' => $user));
    }

}