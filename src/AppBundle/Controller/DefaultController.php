<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\DataTables;


/**
 * Class DefaultController
 *
 *
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/app/{tableName}", defaults={"tableName" = null}, name="homepage")
     * @Template()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function indexAction(Request $request, $tableName=null)
    {
        //Entretien d'apréciation
        $vmAssessorDataTable = $this->get('data_tables.manager')->getTable('valuationMeetAssessorTable');
        $vmAssessedDataTable = $this->get('data_tables.manager')->getTable('valuationMeetAssessedTable');

        if ( $tableName == 'vmAssessor' && $response = $vmAssessorDataTable->ProcessRequest($request)) { //
            return $response;
        }
        if($tableName == 'vmAssessed' && $response = $vmAssessedDataTable->ProcessRequest($request)  )
        {
            return $response;
        }
        //Entretien professionnel
        $pmAssessorDataTable = $this->get('data_tables.manager')->getTable('professionalMeetAssessorTable');
        $pmAssessedDataTable = $this->get('data_tables.manager')->getTable('professionalMeetAssessedTable');

        if ( $tableName == 'pmAssessor' && $response = $pmAssessorDataTable->ProcessRequest($request)) { //
            return $response;
        }
        if($tableName == 'pmAssessed' && $response = $pmAssessedDataTable->ProcessRequest($request)  )
        {
            return $response;
        }

        return array('vmAssessorDataTable' => $vmAssessorDataTable,
            'vmAssessedDataTable' => $vmAssessedDataTable,
            'pmAssessorDataTable' => $pmAssessorDataTable,
            'pmAssessedDataTable' => $pmAssessedDataTable
        );
    }

    /**
     * @Route("/app/valuation/assessor/{tableName}", defaults={"tableName" = null}, name="valuationmeet_assessor_list")
     * @Template("AppBundle:Default:datatable.html.twig")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function valuationMeetAssessorListAction(Request $request, $tableName = null){

        //Entretien d'apréciation
        $vmAssessorDataTable = $this->get('data_tables.manager')->getTable('valuationMeetAssessorTable');

        if ( $tableName == 'meetDataTable' && $response = $vmAssessorDataTable->ProcessRequest($request)) { //
            return $response;
        }

        return array("dataTable" => $vmAssessorDataTable,
            "routeName" => "valuationmeet_assessor_list",
            "title" => "Entretiens d'appréciation : Evaluateur",
            "icon" => "fa-star");

    }
    /**
     * @Route("/app/valuation/assessed/{tableName}", defaults={"tableName" = null}, name="valuationmeet_assessed_list")
     * @Template("AppBundle:Default:datatable.html.twig")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function valuationMeetAssesedListAction(Request $request, $tableName = null){

        //Entretien d'apréciation
        $vmAssessedDataTable = $this->get('data_tables.manager')->getTable('valuationMeetAssessedTable');


        if($tableName == 'meetDataTable' && $response = $vmAssessedDataTable->ProcessRequest($request)  )
        {
            return $response;
        }

        return array("dataTable" => $vmAssessedDataTable,
            "routeName" => "valuationmeet_assessed_list",
            "title" => "Entretiens d'appréciation : Evalué",
            "icon" => "fa-star");

    }

    /**
     * @Route("/app/professional/assessor/{tableName}", defaults={"tableName" = null}, name="professionalmeet_assessor_list")
     * @Template("AppBundle:Default:datatable.html.twig")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function professionalMeetAssessorListAction(Request $request, $tableName = null){

        //Entretien professionnel
        $pmAssessorDataTable = $this->get('data_tables.manager')->getTable('professionalMeetAssessorTable');

        if ( $tableName == 'meetDataTable' && $response = $pmAssessorDataTable->ProcessRequest($request)) { //
            return $response;
        }

        return array("dataTable" => $pmAssessorDataTable,
            "routeName" => "professionalmeet_assessor_list",
            "title" => "Entretiens professionnel : Evaluateur",
            "icon" => "fa-briefcase");

    }

    /**
     * @Route("/app/professional/assessed/{tableName}", defaults={"tableName" = null}, name="professionalmeet_assessed_list")
     * @Template("AppBundle:Default:datatable.html.twig")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function professionalMeetAssessedListAction(Request $request, $tableName = null){

        //Entretien professionnel
        $pmAssesserDataTable = $this->get('data_tables.manager')->getTable('professionalMeetAssessedTable');

        if ( $tableName == 'meetDataTable' && $response = $pmAssesserDataTable->ProcessRequest($request)) { //
            return $response;
        }

        return array("dataTable" => $pmAssesserDataTable,
            "routeName" => "professionalmeet_assessed_list",
            "title" => "Entretiens professionnel : Evalué",
            "icon" => "fa-briefcase");

    }

}
