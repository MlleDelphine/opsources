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

    /**
     * @Route("/app/valuation/assessor/{tableName}", defaults={"tableName" = null}, name="valuationmeet_assessor_list")
     * @Template("AppBundle:Default:datatable.html.twig")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function valuationMeetAssessorListAction(Request $request, $tableName = null)
    {

        //Entretien d'apréciation
        $vmAssessorDataTable = $this->get('data_tables.manager')->getTable('valuationMeetAssessorTable');

        if ($tableName == 'meetDataTable' && $response = $vmAssessorDataTable->ProcessRequest($request)) { //
            return $response;
        }

        return array('dataTable' => $vmAssessorDataTable,
            'routeName' => 'valuationmeet_assessor_list',
            'title' => "Entretiens d'appréciation : Evaluateur",
            'createRouteName' => 'new_valuationmeet',
            'icon' => 'fa-star', );
    }
    /**
     * @Route("/app/valuation/assessed/{tableName}", defaults={"tableName" = null}, name="valuationmeet_assessed_list")
     * @Template("AppBundle:Default:datatable.html.twig")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function valuationMeetAssesedListAction(Request $request, $tableName = null)
    {

        //Entretien d'apréciation
        $vmAssessedDataTable = $this->get('data_tables.manager')->getTable('valuationMeetAssessedTable');

        if ($tableName == 'meetDataTable' && $response = $vmAssessedDataTable->ProcessRequest($request)) {
            return $response;
        }

        return array('dataTable' => $vmAssessedDataTable,
            'routeName' => 'valuationmeet_assessed_list',
            'title' => "Entretiens d'appréciation : Evalué",
            'createRouteName' => 'new_valuationmeet',
            'icon' => 'fa-star', );
    }

    /**
     * @Route("/app/professional/assessor/{tableName}", defaults={"tableName" = null}, name="professionalmeet_assessor_list")
     * @Template("AppBundle:Default:datatable.html.twig")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function professionalMeetAssessorListAction(Request $request, $tableName = null)
    {

        //Entretien professionnel
        $pmAssessorDataTable = $this->get('data_tables.manager')->getTable('professionalMeetAssessorTable');

        if ($tableName == 'meetDataTable' && $response = $pmAssessorDataTable->ProcessRequest($request)) { //
            return $response;
        }

        return array('dataTable' => $pmAssessorDataTable,
            'routeName' => 'professionalmeet_assessor_list',
            'title' => 'Entretiens professionnel : Evaluateur',
            'createRouteName' => 'new_professionalmeet',
            'icon' => 'fa-briefcase', );
    }

    /**
     * @Route("/app/professional/assessed/{tableName}", defaults={"tableName" = null}, name="professionalmeet_assessed_list")
     * @Template("AppBundle:Default:datatable.html.twig")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function professionalMeetAssessedListAction(Request $request, $tableName = null)
    {

        //Entretien professionnel
        $pmAssesserDataTable = $this->get('data_tables.manager')->getTable('professionalMeetAssessedTable');

        if ($tableName == 'meetDataTable' && $response = $pmAssesserDataTable->ProcessRequest($request)) { //
            return $response;
        }

        return array('dataTable' => $pmAssesserDataTable,
            'routeName' => 'professionalmeet_assessed_list',
            'title' => 'Entretiens professionnel : Evalué',
            'createRouteName' => 'new_professionalmeet',
            'icon' => 'fa-briefcase', );
    }

    /**
     * @Route("/app/conditions/assessor/{tableName}", defaults={"tableName" = null}, name="conditionsmeet_assessor_list")
     * @Template("AppBundle:Default:datatable.html.twig")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function conditionsMeetAssessorListAction(Request $request, $tableName = null)
    {

        //Entretien professionnel
        $cmAssessorDataTable = $this->get('data_tables.manager')->getTable('conditionsMeetAssessorTable');

        if ($tableName == 'meetDataTable' && $response = $cmAssessorDataTable->ProcessRequest($request)) { //
            return $response;
        }

        return array('dataTable' => $cmAssessorDataTable,
            'routeName' => 'conditionsmeet_assessor_list',
            'title' => 'Entretiens sur les conditions de travail : Evaluateur',
            'createRouteName' => 'new_conditionsmeet',
            'icon' => 'fa-tachometer', );
    }

    /**
     * @Route("/app/conditions/assessed/{tableName}", defaults={"tableName" = null}, name="conditionsmeet_assessed_list")
     * @Template("AppBundle:Default:datatable.html.twig")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function conditionsMeetAssessedListAction(Request $request, $tableName = null)
    {

        //Entretien professionnel
        $cmAssesserDataTable = $this->get('data_tables.manager')->getTable('conditionsMeetAssessedTable');

        if ($tableName == 'meetDataTable' && $response = $cmAssesserDataTable->ProcessRequest($request)) { //
            return $response;
        }

        return array('dataTable' => $cmAssesserDataTable,
            'routeName' => 'conditionsmeet_assessed_list',
            'title' => 'Entretiens sur les conditions de travail : Evalué',
            'createRouteName' => 'new_conditionsmeet',
            'icon' => 'fa-tachometer', );
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
