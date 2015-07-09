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
        $attributes = $this->get('app.customfields_parser')->parseYamlConf('professional_meet', 'fields');
        $meet = new ProfessionalMeet();

        $form = $this->get('app.prepopulate_entity')->populateProfessionalMeet($meet, $attributes);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                if($form->get('save')->isClicked()){
                    $status = $em->getRepository('FormGeneratorBundle:Status')->findOneBy(array('code' => "pending_m"));
                    $meet->setStatus($status);
                }
                elseif($form->get('submit')->isClicked()){
                    $status = $em->getRepository('FormGeneratorBundle:Status')->findOneBy(array('code' => "validated_m"));
                    $meet->setStatus($status);
                }
                $meet->setAssessor($this->getUser());
                $em->persist($meet);
                $em->flush();
            }
        }
        return $this->forward('FormGeneratorBundle:ProfessionalMeet:edit', array('id' => $meet->getId()));
    }


    /**
     * Displays a form to edit an existing ProfessionalMeet.
     *
     * @Route("/edit/{id}", requirements={"id" = "\d+"}, name="edit_professionalmeet")
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FormGeneratorBundle:ProfessionalMeet')->find($id);

        if($this->get('app.accesscontrol_meet')->canAccess($entity)) {
            $uiTab = $this->get('app.customfields_parser')->parseYamlConf('professional_meet_ui');

            $attributes = $this->get('app.customfields_parser')->parseYamlConf('professional_meet', 'fields');
            $name = $this->get('app.customfields_parser')->parseYamlConf('professional_meet', 'name');


            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Formation entity.');
            }

            $form = $this->get('app.prepopulate_entity')->populateProfessionalMeetForEdit($entity, $attributes);

            return $this->render(
                'FormGeneratorBundle:ProfessionalMeet:generator.html.twig',
                array(
                    'form' => $form->createView(),
                    'uiTab' => $uiTab,
                    'name' => $name,
                )
            );
        }
        else{
            return $this->forward('AppBundle:Default:index');
        }
    }

    /**
     * Edits an existing Formation entity.
     *
     * @Route("/update/{id}", requirements={"id" = "\d+"}, name="update_professionalmeet")
     */
    public function updateAction(Request $request, $id){

        $em = $this->getDoctrine()->getManager();
        $meet = $em->getRepository('FormGeneratorBundle:ProfessionalMeet')->find($id);
        if($this->get('app.accesscontrol_meet')->canAccess($meet)) {
            $uiTab = $this->get('app.customfields_parser')->parseYamlConf('professional_meet_ui');

            $attributes = $this->get('app.customfields_parser')->parseYamlConf('professional_meet', 'fields');
            $name = $this->get('app.customfields_parser')->parseYamlConf('professional_meet', 'name');

            $form = $this->get('app.prepopulate_entity')->populateProfessionalMeetForEdit($meet, $attributes);

            if ($request->isMethod('POST') or $request->isMethod('PUT')) {
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    //Le manager édite
                    if ($this->getUser() === $meet->getAssessor()) {
                        if ($form->get('save')->isClicked()) {
                            $status = $em->getRepository('FormGeneratorBundle:Status')->findOneBy(
                                array('code' => "pending_m")
                            );
                            $meet->setStatus($status);
                        } elseif ($form->get('submit')->isClicked()) {
                            $status = $em->getRepository('FormGeneratorBundle:Status')->findOneBy(
                                array('code' => "validated_m")
                            );
                            $meet->setStatus($status);
                        } elseif ($form->get('refused')->isClicked()) {
                            $status = $em->getRepository('FormGeneratorBundle:Status')->findOneBy(
                                array('code' => "refused_m")
                            );
                            $meet->setStatus($status);
                        }
                    } // L'évalué édite
                    elseif ($this->getUser() == $meet->getAssessed()) {
                        if ($form->get('submit')->isClicked()) {
                            $status = $em->getRepository('FormGeneratorBundle:Status')->findOneBy(
                                array('code' => "validated_e")
                            );
                            $meet->setStatus($status);
                        } elseif ($form->get('refused')->isClicked()) {
                            $status = $em->getRepository('FormGeneratorBundle:Status')->findOneBy(
                                array('code' => "refused_e")
                            );
                            $meet->setStatus($status);
                        }
                    }
                    $em->persist($meet);
                    $em->flush();
                }
            }

            return $this->render(
                'FormGeneratorBundle:ProfessionalMeet:generator.html.twig',
                array(
                    'entity' => $meet,
                    'name' => $name,
                    'uiTab' => $uiTab,
                    'form' => $form->createView(),
                )
            );
        }
        else{
            return $this->forward('AppBundle:Default:Index');
        }
    }
}