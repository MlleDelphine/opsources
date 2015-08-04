<?php

namespace GeneratorBundle\Controller;

use GeneratorBundle\Entity\OpusSheet;
use GeneratorBundle\Entity\OpusSheetType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Entity\User;

class DefaultController extends Controller
{
    //On va passer l'ID d'une fiche pour l'éditer

    public function indexAction($codeText)
    {
        $uiTab = $this->get('app.customfields_parser')->parseYamlConf($campaign->getConfFileUi());
        $allAttributes = $this->get('app.customfields_parser')->parseYamlConf($campaign->getConfFile(), 'fields');
        $name = $this->get('app.customfields_parser')->parseYamlConf($campaign->getConfFile(), 'name');

        $opusSheet = new OpusSheet();

        $form = $this->get('app.prepopulate_entity')->populateOpusSheet($opusSheet, $allAttributes);

        return $this->render('GeneratorBundle:Default:generator.html.twig', array(
            'entity' => $opusSheet,
            'name' => $name,
            'uiTab' => $uiTab,
            'form' => $form->createView(),
        ));
    }

    /**
     * On crée une fiche orpheline, hors précréation grâce à une campagne
     */
    public function newAction($idUser, $strCodeType)
    {
        $em = $this->getDoctrine()->getManager();
        $userLogged = $this->get('security.token_storage')->getToken()->getUser();

        $opusSheet = new OpusSheet();

        $sheetType = $em->getRepository('GeneratorBundle:OpusSheetType')->findOneByStrCode($strCodeType);
        $userEvaluate = $em->getRepository('UserBundle:User')->findOneById($idUser);
        $generatedStatus = $em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode('generee');

        $opusSheet->setEvaluate($userEvaluate);
        $opusSheet->setEvaluator($userLogged);
        $opusSheet->setStatus($generatedStatus);

        $opusSheet = $this->setCampaignAndTemplate($opusSheet, $sheetType, $userEvaluate);

        $templateFile = $opusSheet->getOpusTemplate()->getConfFile();

        $allAttributes = $this->get('app.customfields_parser')->parseYamlConf($templateFile, 'fields');

        //On persist dans populateOpusSheet
        $form = $this->get('app.prepopulate_entity')->populateOpusSheet($opusSheet, $allAttributes);

        return $this->redirect($this->generateUrl('generator_editsheet', array('id' => $opusSheet->getId())));

    }

    /**
     * Affichage du formulaire d'édition
     *
     * @param $id
     * @return Response
     */

    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $opusSheet = $em->getRepository('GeneratorBundle:OpusSheet')->findOneById($id);
        if($this->get('app.accesscontrol_sheet')->canAccess($opusSheet)) {

            if (!$opusSheet) {
                throw $this->createNotFoundException('Unable to find OpusSheet entity.');
            }

            $opusTemplate = $opusSheet->getOpusTemplate();
            $template = $opusTemplate->getConfFile();

            $name = $this->get('app.customfields_parser')->parseYamlConf($template, 'name');

            $uiTab = $this->get('app.customfields_parser')->parseYamlConf($template, 'tabs_ui');
            $allAttributes = $this->get('app.customfields_parser')->parseYamlConf($template, 'fields');

            $form = $this->get('app.prepopulate_entity')->createOpusSheetCreateForm($opusSheet, $allAttributes, true);

            return $this->render(
                'GeneratorBundle:Default:generator.html.twig',
                array(
                    'entity' => $opusSheet,
                    'name' => $name,
                    'uiTab' => $uiTab,
                    'form' => $form->createView(),
                )
            );
        }

        return $this->redirect($this->generateUrl('homepage'));


    }

    /**
     * On crée, en BdD, une fiche créée manuellement
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $opusSheet = $em->getRepository('GeneratorBundle:OpusSheet')->find($id);
        if($this->get('app.accesscontrol_sheet')->canAccess($opusSheet)) {
            $actualStatus = $opusSheet->getStatus();

            //Availables status
            $generatedStatus = $em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode('generee');
            $creationStatus = $em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode('creation');
            $vEvaluatedStatus = $em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode(
                'valid_evaluated'
            );
            $vEvaluatorStatus = $em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode(
                'valid_evaluator'
            );
            $vSecEvaluatorStatus = $em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode(
                'valid_second_evaluator'
            );

            $templateFile = $opusSheet->getOpusTemplate()->getConfFile();

            $allAttributes = $this->get('app.customfields_parser')->parseYamlConf($templateFile, 'fields');
            $form = $this->get('app.prepopulate_entity')->populateOpusSheet($opusSheet, $allAttributes, true);

            if ($request->isMethod('POST')) {
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getManager();

                    //Si c'était en génération ou en création
                    if ($actualStatus == $generatedStatus || $actualStatus == $creationStatus) {
                        if ($form->get('save')->isClicked()) {
                            $opusSheet->setStatus($generatedStatus);
                        } elseif ($form->get('validate')->isClicked()) {
                            $opusSheet->setStatus($creationStatus);
                        }
                    } //Si c'était à l'évalué de choisir
                    elseif ($actualStatus == $vEvaluatedStatus) {
                        if ($form->get('invalidate')->isClicked()) {
                            $opusSheet->setStatus($vEvaluatorStatus);
                        } elseif ($form->get('validate')->isClicked()) {
                            $opusSheet->setStatus($vEvaluatorStatus);
                        }
                    } elseif ($actualStatus == $vEvaluatorStatus) {
                        if ($form->get('invalidate')->isClicked()) {
                            $opusSheet->setStatus($vEvaluatedStatus);
                        } elseif ($form->get('validate')->isClicked()) {
                            $opusSheet->setStatus($vSecEvaluatorStatus);
                        }
                    } else {

                    }
                    $em->persist($opusSheet);
                    $em->flush();
                }
            }

            return $this->redirect($this->generateUrl('generator_editsheet', array('id' => $opusSheet->getId())));
        }

        return $this->redirect($this->generateUrl('homepage'));
    }


    public function pdfAction($id)
    {
        $text = $this->get('app.pdfparser')->getSheetToHtml($id);
        /*return $this->render('GeneratorBundle:PDF:view.html.twig', array(
            'html' => $text
        ));*/
        $html = $this->renderView('GeneratorBundle:PDF:view.html.twig', array(
            'html' => $text,
        ));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="file.pdf"',
            )
        );
    }

    protected function setCampaignAndTemplate(OpusSheet $opusSheet, OpusSheetType $sheetType, User $userEvaluate){

        $em = $this->getDoctrine()->getManager();
        $campaignProcessing = $em->getRepository('GeneratorBundle:OpusCampaign')->findOneBy(array('status' => 1, 'type' => $sheetType));

        /*
         * Si une campagne est en cours
         */
        if ($campaignProcessing) {
            $template = $campaignProcessing->getOpusTemplate();
            $opusSheetsNotClosed = $em->getRepository('GeneratorBundle:OpusSheet')->findSheetsNotClosedInCampaign($campaignProcessing, $userEvaluate);
            //S'il n'y a pas de fiche en cours pour cet user dans cette campagne
            if ($opusSheetsNotClosed === null) {
                $opusSheet->setCampaign($campaignProcessing);
                $opusSheet->setOpusTemplate($template);
            } else {
                //Dernir template en date pour ce type
                $template = $em->getRepository('GeneratorBundle:OpusSheetTemplate')->findOneBy(array('type' => $sheetType, 'status' => 1));
                $opusSheet->setOpusTemplate($template);
            }
        } else {
            //Dernir template en date pour ce type
            $template = $em->getRepository('GeneratorBundle:OpusSheetTemplate')->findOneBy(array('type' => $sheetType, 'status' => 1));
            $opusSheet->setOpusTemplate($template);
        }

        return $opusSheet;

    }
}
