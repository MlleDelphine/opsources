<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 22/06/2015
 * Time: 16:39
 */

namespace FormGeneratorBundle\Controller;

use FormGeneratorBundle\Entity\ProfessionalMeet;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FormGeneratorBundle\Service;

/**
 * Class DProfessionalMeetController
 * @package FormGeneratorBundle\Controller
 * @Route("/professional")
 */

class ProfessionalMeetController extends Controller{
    
    /**
     * @Route("/new", name="new_professionalmeet")
     */
    public function newAction()
    {
        $uiTab = $this->get('app.customfields_parser')->parseYamlConf('professional_meet_ui');

        $attributes = $this->get('app.customfields_parser')->parseYamlConf('professional_meet', 'fields');
        $name = $this->get('app.customfields_parser')->parseYamlConf('professional_meet', 'name');
        $meet = new ProfessionalMeet();

        $form = $this->get('app.prepopulate_entity')->populateProfessionalMeet($meet, $attributes);

        return $this->render('FormGeneratorBundle:ProfessionalMeet:generator.html.twig', array(
            'entity' => $meet,
            'name' => $name,
            'uiTab' => $uiTab,
            'form'   => $form->createView(),
        ));
    }


    /**
     * @Route("/create", name="create_professionalmeet")
     */
    public function addAction(Request $request)
    {
        $uiTab = $this->get('app.customfields_parser')->parseYamlConf('professional_meet_ui');

        $attributes = $this->get('app.customfields_parser')->parseYamlConf('professional_meet', 'fields');
        $name = $this->get('app.customfields_parser')->parseYamlConf('professional_meet', 'name');
        $meet = new ProfessionalMeet();

        $form = $this->get('app.prepopulate_entity')->populateProfessionalMeet($meet, $attributes);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($meet);
                $em->flush();
            }
        }
        return $this->render('FormGeneratorBundle:ProfessionalMeet:generator.html.twig', array(
            'entity' => $meet,
            'name' => $name,
            'uiTab' => $uiTab,
            'form'   => $form->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing ProfessionalMeet.
     *
     * @Route("/edit/{id}", requirements={"id" = "\d+"}, name="edit_professionalmeet")
     *
     */
    public function editAction($id)
    {
        $uiTab = $this->get('app.customfields_parser')->parseYamlConf('professional_meet_ui');

        $em = $this->getDoctrine()->getManager();
        $attributes = $this->get('app.customfields_parser')->parseYamlConf('professional_meet', 'fields');
        $name = $this->get('app.customfields_parser')->parseYamlConf('professional_meet', 'name');
        $entity = $em->getRepository('FormGeneratorBundle:ProfessionalMeet')->find($id);


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Formation entity.');
        }

        $form = $this->get('app.prepopulate_entity')->populateProfessionalMeetForEdit($entity, $attributes);

        return $this->render('FormGeneratorBundle:ProfessionalMeet:generator.html.twig', array(
            'form'   => $form->createView(),
            'uiTab' => $uiTab,
            'name' => $name,
        ));
    }

    /**
     * Edits an existing Formation entity.
     *
     * @Route("/update/{id}", requirements={"id" = "\d+"}, name="update_professionalmeet")
     */
    public function updateAction(Request $request, $id){
        $uiTab = $this->get('app.customfields_parser')->parseYamlConf('professional_meet_ui');

        $attributes = $this->get('app.customfields_parser')->parseYamlConf('professional_meet', 'fields');
        $name = $this->get('app.customfields_parser')->parseYamlConf('professional_meet', 'name');
        $em = $this->getDoctrine()->getManager();

        $meet = $em->getRepository('FormGeneratorBundle:ProfessionalMeet')->find($id);

        $form = $this->get('app.prepopulate_entity')->populateProfessionalMeetForEdit($meet, $attributes);

        if ($request->isMethod('POST') or $request->isMethod('PUT')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($meet);
                $em->flush();
            }
        }


        return $this->render('FormGeneratorBundle:ProfessionalMeet:generator.html.twig', array(
            'entity' => $meet,
            'name' => $name,
            'uiTab' => $uiTab,
            'form'   => $form->createView(),
        ));

    }
}