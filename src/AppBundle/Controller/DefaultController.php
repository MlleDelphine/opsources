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
     * @Route("/", name="homepage")
     * @Template()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function indexAction(Request $request, $tableName=null)
    {
//        $vmAssessorDataTable = $this->get('data_tables.manager')->getTable('valuationMeetAssessorTable');
//        $vmAssessedDataTable = $this->get('data_tables.manager')->getTable('valuationMeetAssessedTable');
//        if ( $response = $vmAssessorDataTable->ProcessRequest($request)) { //
//            return $response;
//        }
//        if($response = $vmAssessedDataTable->ProcessRequest($request)  )
//        {
//            return $response;
//        }
//
//        return array('vmAssessorDataTable' => $vmAssessorDataTable,
//            'vmAssessedDataTable' => $vmAssessedDataTable,
//            );

        $request   = $this->getRequest();
        $em        = $this->get('doctrine.orm.entity_manager');

        // process the data table
        $dataTableA = new UserTable();
        $dataTableA->setEm($em);
        $dataTableA->setContainer($this->container);
        if ($tableName == 'dataTableA' && $response = $dataTableA->ProcessRequest($request)) {
            return $response;
        }

        $dataTableB = new UserTableB();
        $dataTableB->setEm($em);
        $dataTableB->setContainer($this->container);
        if ($tableName == 'dataTableB' &&$response = $dataTableB->ProcessRequest($request)) {
            return $response;
        }

        // display html
        return array(
            'columnsA' => $dataTableA->getColumns(),
            'columnsB' => $dataTableB->getColumns(),
        );
    }
}
