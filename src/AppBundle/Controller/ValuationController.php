<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ValuationMeet;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service;

/**
 * Class DefaultController
 * @package AppBundle\Controller
 * @Route("/valuation")
 */

class ValuationController extends Controller
{
    /**
     * @Route("/example", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }


    /**
     * @Route("/new", name="new_valuationmeet")
     */
    public function newAction()
    {
        $attributes = $this->get('app.customfields_parser')->parseYamlConf('valuation_meet', 'fields');
        $meet = new ValuationMeet();

        $form = $this->get('app.prepopulate_entity')->populateValuationMeet($meet, $attributes);

        return $this->render('AppBundle:Default:generator.html.twig', array(
            'entity' => $meet,
            'form'   => $form->createView(),
        ));
    }


    /**
     * @Route("/create", name="create_valuationmeet")
     */
    public function addAction(Request $request)
    {
        $attributes = $this->get('app.customfields_parser')->parseYamlConf('valuation_meet', 'fields');
        $meet = new ValuationMeet();

        $form = $this->get('app.prepopulate_entity')->populateValuationMeet($meet, $attributes);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($meet);
                $em->flush();
            }
        }
        return $this->render('AppBundle:Default:generator.html.twig', array(
            'entity' => $meet,
            'form'   => $form->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing Formation ValuationMeet.
     *
     * @Route("/edit/{id}", requirements={"id" = "\d+"}, name="edit_valuationmeet")
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $attributes = $this->get('app.customfields_parser')->parseYamlConf('valuation_meet', 'fields');

        $entity = $em->getRepository('AppBundle:ValuationMeet')->find($id);


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Formation entity.');
        }

        $form = $this->get('app.prepopulate_entity')->populateValuationMeetForEdit($entity, $attributes);

        return $this->render('AppBundle:Default:generator.html.twig', array(
            'form'   => $form->createView(),
        ));
    }

    /**
     * Edits an existing Formation entity.
     *
     * @Route("/update/{id}", requirements={"id" = "\d+"}, name="update_valuationmeet")
     */
    public function updateAction(Request $request, $id){

        $attributes = $this->get('app.customfields_parser')->parseYamlConf('valuation_meet', 'fields');
        $em = $this->getDoctrine()->getManager();

        $meet = $em->getRepository('AppBundle:ValuationMeet')->find($id);

        $form = $this->get('app.prepopulate_entity')->populateValuationMeetForEdit($meet, $attributes);

        if ($request->isMethod('POST') or $request->isMethod('PUT')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($meet);
                $em->flush();
            }
        }


        return $this->render('AppBundle:Default:generator.html.twig', array(
            'entity' => $meet,
            'form'   => $form->createView(),
        ));

    }
}