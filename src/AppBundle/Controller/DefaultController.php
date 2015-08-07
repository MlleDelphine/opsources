<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
     * @return array
     */
    public function indexAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('UserBundle:User')->findAll();
        $types = $em->getRepository('GeneratorBundle:OpusSheetType')->findAll();
        $templates = $em->getRepository('GeneratorBundle:OpusSheetTemplate')->findAll();
        $fiches = $em->getRepository('GeneratorBundle:OpusSheet')->findAll();

        /*foreach($users as $u)
        {
            foreach($u->getOpusSheetsEvaluator() as $e){
                $user = $u;
                break 2;
            }
        }*/

        $user = $em->getRepository('UserBundle:User')->find($user->getId());
        return array(
            'user' => $user,
            'users' => $users,
            'types' => $types,
            'templates' => $templates,
            'fiches' => $fiches
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
}
