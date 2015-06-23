<?php

namespace FormGeneratorBundle\Controller;

use FormGeneratorBundle\Entity\ValuationMeet;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FormGeneratorBundle\Service;

/**
 * Class ValuationController
 * @package FormGeneratorBundle\Controller
 * @Route("/valuation")
 */

class ValuationController extends Controller
{
    /**
     * @Route("/new", name="new_valuationmeet")
     */
    public function newAction()
    {
        $attributes = $this->get('app.customfields_parser')->parseYamlConf('valuation_meet', 'fields');
        $name = $this->get('app.customfields_parser')->parseYamlConf('valuation_meet', 'name');
        $meet = new ValuationMeet();

        $form = $this->get('app.prepopulate_entity')->populateValuationMeet($meet, $attributes);

        return $this->render('FormGeneratorBundle:Valuation:generator.html.twig', array(
            'entity' => $meet,
            'name' => $name,
            'form'   => $form->createView(),
        ));
    }


    /**
     * @Route("/create", name="create_valuationmeet")
     */
    public function addAction(Request $request)
    {
        $attributes = $this->get('app.customfields_parser')->parseYamlConf('valuation_meet', 'fields');
        $name = $this->get('app.customfields_parser')->parseYamlConf('valuation_meet', 'name');
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
        return $this->render('FormGeneratorBundle:Valuation:generator.html.twig', array(
            'entity' => $meet,
            'name' => $name,
            'form'   => $form->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing ValuationMeet.
     *
     * @Route("/edit/{id}", requirements={"id" = "\d+"}, name="edit_valuationmeet")
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $attributes = $this->get('app.customfields_parser')->parseYamlConf('valuation_meet', 'fields');
        $name = $this->get('app.customfields_parser')->parseYamlConf('valuation_meet', 'name');

        $entity = $em->getRepository('FormGeneratorBundle:ValuationMeet')->find($id);


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Formation entity.');
        }

        $form = $this->get('app.prepopulate_entity')->populateValuationMeetForEdit($entity, $attributes);

        return $this->render('FormGeneratorBundle:Valuation:generator.html.twig', array(
            'form'   => $form->createView(),
            'name' => $name,
        ));
    }

    /**
     * Edits an existing Formation entity.
     *
     * @Route("/update/{id}", requirements={"id" = "\d+"}, name="update_valuationmeet")
     */
    public function updateAction(Request $request, $id){

        $attributes = $this->get('app.customfields_parser')->parseYamlConf('valuation_meet', 'fields');
        $name = $this->get('app.customfields_parser')->parseYamlConf('valuation_meet', 'name');
        $em = $this->getDoctrine()->getManager();

        $meet = $em->getRepository('FormGeneratorBundle:ValuationMeet')->find($id);

        $form = $this->get('app.prepopulate_entity')->populateValuationMeetForEdit($meet, $attributes);

        if ($request->isMethod('POST') or $request->isMethod('PUT')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($meet);
                $em->flush();
            }
        }


        return $this->render('FormGeneratorBundle:Valuation:generator.html.twig', array(
            'entity' => $meet,
            'name' => $name,
            'form'   => $form->createView(),
        ));

    }
}