<?php

/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 30/06/2015
 * Time: 09:54.
 */

namespace AppBundle\DataTables;

use Brown298\DataTablesBundle\MetaData as DataTable;
use Brown298\DataTablesBundle\Model\DataTable\QueryBuilderDataTableInterface;
use Brown298\DataTablesBundle\Test\DataTable\QueryBuilderDataTable;
use Symfony\Component\HttpFoundation\Request;

/**
 * Default controller.
 *
 *
 * @DataTable\Table(id="valuationMeetAssessorTable")
 */
class ValuationMeetAssessorTable extends QueryBuilderDataTable implements QueryBuilderDataTableInterface
{
    /**
     * @var datetime
     * @DataTable\Column(source="valuationMeet.meetDate", name="Date",  class="text-center")
     * @DataTable\Column(sortable=true)
     * @DataTable\Format(dataFields={"dateAttribute":"valuationMeet.meetDate"}, template="AppBundle:DataTable:_date_template.html.twig")
     */
    public $meetDate;

    /**
     * @var int
     * @DataTable\Column(source="valuationMeet.id", name="ID", class="")
     * @DataTable\Column(sortable=true)
     */
    public $id;

    /**
     * @var string
     * @DataTable\Column(source="valuationMeet.name", name="Intitulé",  class="text-center")
     * @DataTable\Column(sortable=true)
     * @DataTable\DefaultSort()
     */
    public $name;

    /**
     * @var string
     * @DataTable\Column(source="valuationMeet.assessor", name="Evaluateur",  class="text-center")
     * @DataTable\Column(sortable=true)
     * @DataTable\Format(dataFields={"person":"valuationMeet.assessor"}, template="AppBundle:DataTable:_person_template.html.twig")
     * @DataTable\DefaultSort()
     */
    public $assessor;

    /**
     * @var string
     * @DataTable\Column(source="valuationMeet.assessed", name="Evalué",  class="text-center")
     * @DataTable\Column(sortable=true)
     * @DataTable\Format(dataFields={"person":"valuationMeet.assessed"}, template="AppBundle:DataTable:_person_template.html.twig")
     * @DataTable\DefaultSort()
     */
    public $assessed;

    /**
     * @var string
     * @DataTable\Column(source="valuationMeet.status.name", name="Statut",  class="text-center")
     * @DataTable\Column(sortable=true)
     * @DataTable\DefaultSort()
     */
    public $status;

    /**
     * @DataTable\Column(source="", name="Actions",  class="")
     * @DataTable\Format(dataFields={"id":"valuationMeet.id"}, template="AppBundle:DataTable:_dataTables_action_editval.html.twig")
     */
    public $action;

    /**
     * @var bool hydrate results to doctrine objects
     */
    public $hydrateObjects = true;

    /**
     * getQueryBuilder.
     *
     * @param Request $request
     */
    public function getQueryBuilder(Request $request = null)
    {
        $assessor = $this->container->get('security.context')->getToken()->getUser();
        $valuationMeetRepository = $this->em->getRepository('FormGeneratorBundle:ValuationMeet');
        $qb = $valuationMeetRepository->createQueryBuilder('valuationMeet')
            ->leftJoin('valuationMeet.assessor', 'aor')
            ->andWhere('aor.id = :assessorId')
            ->setParameter('assessorId', $assessor->getId());

        return $qb;
    }
}
