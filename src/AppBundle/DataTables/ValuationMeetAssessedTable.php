<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 30/06/2015
 * Time: 09:54
 */

namespace AppBundle\DataTables;

use Brown298\DataTablesBundle\MetaData as DataTable;
use Brown298\DataTablesBundle\Model\DataTable\QueryBuilderDataTableInterface;
use Brown298\DataTablesBundle\Test\DataTable\QueryBuilderDataTable;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\EngineInterface;

/**
 * Default controller
 *
 * @package AppBundle\DataTables
 *
 * @DataTable\Table(id="valuationMeetAssessedTable")
 *
 *
 */
class ValuationMeetAssessedTable extends QueryBuilderDataTable implements QueryBuilderDataTableInterface{

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
     * @var bool hydrate results to doctrine objects
     */
    public $hydrateObjects = true;

    /**
     * getQueryBuilder
     *
     * @param Request $request
     *
     * @return null
     */
    public function getQueryBuilder(Request $request = null)
    {
        $assessed = $this->container->get('security.context')->getToken()->getUser();

        $valuationMeetRepository = $this->em->getRepository('FormGeneratorBundle:ValuationMeet');
        $qb = $valuationMeetRepository->createQueryBuilder('valuationMeet')
            ->leftJoin('valuationMeet.assessed', 'aed')
            ->andWhere('aed.id = :assessedId')
            ->setParameter("assessedId", $assessed->getId());


        return $qb;
    }
}