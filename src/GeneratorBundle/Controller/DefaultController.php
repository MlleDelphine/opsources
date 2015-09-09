<?php

namespace GeneratorBundle\Controller;

use GeneratorBundle\Entity\OpusCampaign;
use GeneratorBundle\Entity\OpusSheet;
use GeneratorBundle\Entity\OpusSheetType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Entity\User;

class DefaultController extends Controller
{
    //On va passer l'ID d'une fiche pour l'éditer

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $userLogged = $this->get('security.token_storage')->getToken()->getUser();
        $userBdd = $em->getRepository("UserBundle:User")->find($userLogged->getId());

        $opusSheets = $userBdd->getOpusSheetsEvaluator();
    }

    /**
     * On crée une fiche orpheline, hors précréation grâce à une campagne
     */
    public function newAction(Request $request, $idUser, $strCodeType)
    {

        $em = $this->getDoctrine()->getManager();
        $userLogged = $this->get('security.token_storage')->getToken()->getUser();
        //on récup celui de la BD sinon ça crée des conflits d'ID
        $userLogged = $em->getRepository('UserBundle:User')->find($userLogged->getId());

        $opusSheet = new OpusSheet();

        $sheetType = $em->getRepository('GeneratorBundle:OpusSheetType')->findOneByStrCode($strCodeType);
        $userEvaluate = $em->getRepository('UserBundle:User')->findOneById($idUser);
        $generatedStatus = $em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode('generee');

        $opusSheet->setEvaluate($userEvaluate);
        $opusSheet->setEvaluator($userEvaluate->getManager());

        $opusSheet->setStatus($generatedStatus);

        $opusSheet = $this->setCampaignAndTemplate($opusSheet, $sheetType, $userEvaluate);

        $templateFile = $opusSheet->getOpusTemplate()->getConfFile();

        $allAttributes = $this->get('app.customfields_parser')->parseYamlConf($templateFile, 'fields');

        //On persist dans populateOpusSheet
        $form = $this->get('app.prepopulate_entity')->populateOpusSheet($opusSheet, $allAttributes);


        if(!$request->isXmlHttpRequest()){
            return $this->redirect($this->generateUrl('generator_editsheet', array('id' => $opusSheet->getId())));
        }

        return new Response('Success');

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

        if (!$opusSheet) {
            throw $this->createNotFoundException('Unable to find OpusSheet entity.');
        }

        if($this->get('app.accesscontrol_sheet')->canAccess($opusSheet)) {

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
            $vFinalEvaluatorStatus = $em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode(
                'valid_final_evaluator'
            );
            $vSecEvaluatorStatus = $em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode(
                'valid_second_evaluator'
            );

            $vRHStatus = $em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode(
                'valid_RH'
            );

            $vCloseStatus = $em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode(
                'close'
            );

            $vCloseFStatus = $em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode(
                'close_finish'
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
                        //Si le manager enregistre --> creation
                        //Si le manager valide --> en attente de validation par l'évalué

                        if ($form->get('save')->isClicked()) {
                            $opusSheet->setStatus($creationStatus);
                        } elseif ($form->get('validate')->isClicked()) {
                            $opusSheet->setStatus($vEvaluatedStatus);
                        }
                    } //Si c'était à l'évalué de choisir
                    elseif ($actualStatus == $vEvaluatedStatus) {
                        //Si l'évalué invalide --> en attente de validation par l'évaluateur (mais il ajoute un commentaire )
                        //Si l'évalué valide --> en attente de validation par l'évaluateur
                        if ($form->get('invalidate')->isClicked()) {
                            $opusSheet->setStatus($vEvaluatorStatus);
                        } elseif ($form->get('validate')->isClicked()) {
                            $opusSheet->setStatus($vFinalEvaluatorStatus);
                        }
                    } elseif ($actualStatus == $vEvaluatorStatus) {
                        //Si l'évaluateur invalide --> retour à l'évalué
                        //Si l'évaluateur valide --> Renvoie à l'évalué
                        if ($form->get('invalidate')->isClicked()) {
                            $opusSheet->setStatus($vEvaluatedStatus);
                        } elseif ($form->get('validate')->isClicked()) {
                            $opusSheet->setStatus($vEvaluatedStatus);
                        }
                    } elseif ($actualStatus == $vFinalEvaluatorStatus) {
                        //Si l'utilisateur était OK - l'évaluateur peut refuser ou envoyer au DRH (s'il envoie au RH pas de modif possible)
                        if ($form->get('invalidate_for_rh')->isClicked()) {
                            $opusSheet->setStatus($vEvaluatorStatus);
                        } elseif ($form->get('validate_rh')->isClicked()) {
                            $opusSheet->setStatus($vRHStatus);
                        }

                    }
                    elseif ($actualStatus == $vRHStatus) {
                        //Si l'utilisateur était OK - l'évaluateur peut refuser ou envoyer au DRH (s'il envoie au RH pas de modif possible)
                        if ($form->get('invalidate_by_rh')->isClicked()) {
                            $opusSheet->setStatus($vCloseFStatus);
                        } elseif ($form->get('validate_by_rh')->isClicked()) {
                            $opusSheet->setStatus($vCloseStatus);
                        }

                    }

                    $em->persist($opusSheet);
                    $em->flush();

                }
            }

            return $this->redirect($this->generateUrl('generator_editsheet', array('id' => $opusSheet->getId())));
        }

        return $this->redirect($this->generateUrl('homepage'));
    }

    public function updateTabAction(Request $request,OpusSheet $opusSheet, $tab){
        $em = $this->getDoctrine()->getManager();
        $response = new Response();
        if($this->get('app.accesscontrol_sheet')->canAccess($opusSheet)) {
            $actualStatus = $opusSheet->getStatus();

            $templateFile = $opusSheet->getOpusTemplate()->getConfFile();


            $allAttributes = $this->get('app.customfields_parser')->parseYamlConf($templateFile, 'fields', $tab);

            $form = $this->get('app.prepopulate_entity')->populateOpusSheet($opusSheet, $allAttributes, true);

            if ($request->isMethod('POST')) {

                $form->handleRequest($request);
                if ($form->isValid()) {
                    $em->persist($opusSheet);
                    $em->flush();

                    return $response->setStatusCode(200);
                }
            }
        }

        return $response->setStatusCode(500);


    }

    public function pdfAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $sheet = $em->getRepository("GeneratorBundle:OpusSheet")->find($id);
        $text = $this->get('app.pdfparser')->getSheetToHtml($id);

        $fileName = $sheet->getEvaluate()->getLastName().' '.$sheet->getEvaluate()->getFirstName().' - '.$sheet->getOpusTemplate()->getType()->getName().' - '.$sheet->getCreatedAt()->format('d-m-Y'). ' -- '.date('d-m-Y H:i:s');
        $html = $this->renderView('GeneratorBundle:PDF:view.html.twig', array(
            'html' => $text,
        ));


        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html, array('encoding' => 'utf-8', 'orientation'=>'Landscape')),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
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
