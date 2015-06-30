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
     * @Route("/{tableName}", defaults={"tableName" = null}, name="homepage")
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

//        $em        = $this->get('doctrine.orm.entity_manager');
//
//        // process the data table
//        $dataTableA = new DataTables\ValuationMeetAssessorTable();
//        $dataTableA->setEm($em);
//        $dataTableA->setContainer($this->container);
//        if ($tableName == 'dataTableA' && $response = $dataTableA->ProcessRequest($request)) {
//            return $response;
//        }
//
//        $dataTableB = new DataTables\ValuationMeetAssessedTable();
//        $dataTableB->setEm($em);
//        $dataTableB->setContainer($this->container);
//        if ($tableName == 'dataTableB' && $response = $dataTableB->ProcessRequest($request)) {
//            return $response;
//        }
//
//        // display html
//        return array(
//            'columnsA' => $dataTableA->getColumns(),
//            'columnsB' => $dataTableB->getColumns(),
//        );
    }
}
