<?php

namespace AppBundle\Controller;

use GeneratorBundle\Entity\OpusCampaign;
use GeneratorBundle\Entity\OpusSheet;
use GeneratorBundle\Form\Campaign\OpusCampaignType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Class DefaultController.
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @param $tableName
     * @return array
     */
    public function indexAction(Request $request, $tableName = null)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('UserBundle:User')->findAll();
        $types = $em->getRepository('GeneratorBundle:OpusSheetType')->findAll();
        $templates = $em->getRepository('GeneratorBundle:OpusSheetTemplate')->findAll();
        $fiches = $em->getRepository('GeneratorBundle:OpusSheet')->findAll();
        $opusCampaigns = $em->getRepository('GeneratorBundle:OpusCampaign')->findBy(array(),array('id' => 'ASC'));

        $opusCampaign = new OpusCampaign();

        $form = $this->get('form.factory')->create(new OpusCampaignType(), $opusCampaign);

        if($form->handleRequest($request)->isValid()){
            $em->persist($opusCampaign);
            $em->flush();

            $flash = "Campagne créée avec succès";

            if($opusCampaign->getStatus() == 1){
                $associateSheetToCampaign = $this->associateSheetToCampaign($opusCampaign, $user);
                $associatedSheets = $associateSheetToCampaign[0];
                $createdSheets = $associateSheetToCampaign[1];
                $flash = "Campagne créée avec succès<br/> Fiches associées : ".$associatedSheets."<br/> Fiches créées et associées : ".$createdSheets;
            }

            $this->get('session')->getFlashBag()->add(
                'info',
                'INFO : '.$flash);

            return $this->redirect($this->generateUrl('homepage'));
        }

        /*foreach($users as $u)
        {
            foreach($u->getOpusSheetsEvaluator() as $e){
                $user = $u;
                break 2;
            }
        }*/

        $dataTableManagementCampaign = $this->get('data_tables.manager')->getTable('OpusCampaignTable');
        if ($tableName == 'OpusCampaignTable' && $response = $dataTableManagementCampaign->ProcessRequest($request)) {
            return $response;
        }

        $dataTableClosedSheets = $this->get('data_tables.manager')->getTable('OpusSheetTable');
        if ($tableName == 'OpusSheetTable' && $response = $dataTableClosedSheets->ProcessRequest($request)) {
            return $response;
        }

        return array(
            'user' => $user,
            'users' => $users,
            'types' => $types,
            'templates' => $templates,
            'fiches' => $fiches,
            'opusCampaigns' => $opusCampaigns,
            'formOpusCampaign' => $form->createView(),
            'dataTableManagementCampaign' => $dataTableManagementCampaign,
            'dataTableClosedSheets' => $dataTableClosedSheets
        );
    }

    /**
     * @Route("/{tableName}", defaults={"tableName" = null}, name="datatables", condition="request.isXmlHttpRequest()")
     * @Template()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @param $tableName
     * @return array
     */
    public function datatablesAction(Request $request, $tableName = null)
    {
        $dataTableManagementCampaign = $this->get('data_tables.manager')->getTable('OpusCampaignTable');
        if ($tableName == 'OpusCampaignTable' && $response = $dataTableManagementCampaign->ProcessRequest($request)) {
            return $response;
        }

        $dataTableClosedSheets = $this->get('data_tables.manager')->getTable('OpusSheetTable');
        if ($tableName == 'OpusSheetTable' && $response = $dataTableClosedSheets->ProcessRequest($request)) {
            return $response;
        }

        return array(
            'dataTableManagementCampaign' => $dataTableManagementCampaign,
            'dataTableClosedSheets' => $dataTableClosedSheets
        );
    }

    /**
     * @Route("/excelexports", name="excelexports")
     * @Template("AppBundle:Default/Includes:exports.html.twig")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function listAllExportsAction(){

        $em = $this->getDoctrine()->getManager();
        $allSheetType = $em->getRepository("GeneratorBundle:OpusSheetType")->findAll();
        $results = array();

        foreach ($allSheetType as $type) {
            $allTemplateByType = $em->getRepository("GeneratorBundle:OpusSheetTemplate")->findByType($type);
            foreach ($allTemplateByType as $template) {
                $templateFile = $template->getConfFile();
                $allAttributes = $this->get('app.customfields_parser')->parseYamlConf($templateFile, 'fields');
                foreach($allAttributes['attr'] as $k => $attr){
                    if(array_key_exists('export_name', $attr)){

                        if(!array_key_exists($type->getId(), $results)){
                            $results[$type->getId()] = array('type' => $type);
                        }
                        if(array_key_exists($type->getId(), $results) && (!array_key_exists('templates', $results[$type->getId()]) || !array_key_exists($template->getId(), $results[$type->getId()]['templates']))) {
                            $results[$type->getId()]['templates'][$template->getId()]['template'] = $template;
                        }

                        $results[$type->getId()]['templates'][$template->getId()]['options'][] = array('id' => $attr['id'], 'export_name' => $attr['export_name'], 'export_desc' => $attr['export_desc']);
                    }
                }
            }
        }

        return array('exports' => $results);

    }

    /**
     * @Route("/edit/campaign/{idCampaign}", name="edit_campaign")
     * @Template()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function editCampaignAction(Request $request, $idCampaign)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $opusCampaign = $em->getRepository('GeneratorBundle:OpusCampaign')->find($idCampaign);

        $form = $this->get('form.factory')->create(new OpusCampaignType(), $opusCampaign);

        if($form->handleRequest($request)->isValid()){
            $em->persist($opusCampaign);
            $em->flush();

            $flash = "Campagne éditée avec succès";

//            if($opusCampaign->getStatus() == 1){
//                $associateSheetToCampaign = $this->associateSheetToCampaign($opusCampaign, $user);
//                $associatedSheets = $associateSheetToCampaign[0];
//                $createdSheets = $associateSheetToCampaign[1];
//                $flash = "Campagne éditée avec succès<br/> Fiches associées : ".$associatedSheets."<br/> Fiches créées et associées : ".$createdSheets;
//            }

            $this->get('session')->getFlashBag()->add(
                'info',
                'INFO : '.$flash);

            return $this->redirect($this->generateUrl('homepage'));
        }


        return array(
            'formOpusCampaign'=>$form->createView()
        );
    }



    protected function associateSheetToCampaign(OpusCampaign $opusCampaign){
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('UserBundle:User')->findUsersWithManager();

        $return = array();
        $associatedSheets = 0;
        $createdSheets = 0;

        foreach($users AS $user){
            $opusSheets = $em->getRepository('GeneratorBundle:OpusSheet')->findSheetsWithoutCampaign($user, $opusCampaign);

            if(!empty($opusSheets)){
                foreach($opusSheets AS $opusSheet){
                    $opusSheet->setCampaign($opusCampaign);
                    $em->persist($opusSheet);
                    $associatedSheets++;
                }
            }else{
                $newOpusSheet = new OpusSheet();
                $opusSheetStatus = $em->getRepository('GeneratorBundle:OpusSheetStatus')->findOneByStrCode('generee');
                $newOpusSheet
                    ->setStatus($opusSheetStatus)
                    ->setEvaluate($user)
                    ->setEvaluator($user->getManager())
                    ->setCampaign($opusCampaign)
                    ->setOpusTemplate($opusCampaign->getOpusTemplate());
                $em->persist($newOpusSheet);
                $createdSheets++;
            }
        }

        $em->flush();

        array_push($return, $associatedSheets, $createdSheets);

        return $return;
    }
}
