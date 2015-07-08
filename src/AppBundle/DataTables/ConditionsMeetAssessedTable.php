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
 * @DataTable\Table(id="conditionsMeetAssessedTable")
 *
 *
 */
class ConditionsMeetAssessedTable extends QueryBuilderDataTable implements QueryBuilderDataTableInterface{

    /**
     * @var datetime
     * @DataTable\Column(source="conditionsMeet.meetDate", name="Date",  class="text-center")
     * @DataTable\Column(sortable=true)
     * @DataTable\Format(dataFields={"dateAttribute":"conditionsMeet.meetDate"}, template="AppBundle:DataTable:_date_template.html.twig")
     */
    public $meetDate;

    /**
     * @var int
     * @DataTable\Column(source="conditionsMeet.id", name="ID", class="")
     * @DataTable\Column(sortable=true)
     */
    public $id;

    /**
     * @var string
     * @DataTable\Column(source="conditionsMeet.name", name="Intitulé",  class="text-center")
     * @DataTable\Column(sortable=true)
     * @DataTable\DefaultSort()
     */
    public $name;

    /**
     * @var string
     * @DataTable\Column(source="conditionsMeet.assessor", name="Evaluateur",  class="text-center")
     * @DataTable\Column(sortable=true)
     * @DataTable\Format(dataFields={"person":"conditionsMeet.assessor"}, template="AppBundle:DataTable:_person_template.html.twig")
     * @DataTable\DefaultSort()
     */
    public $assessor;

    /**
     * @var string
     * @DataTable\Column(source="conditionsMeet.assessed", name="Evalué",  class="text-center")
     * @DataTable\Column(sortable=true)
     * @DataTable\Format(dataFields={"person":"conditionsMeet.assessed"}, template="AppBundle:DataTable:_person_template.html.twig")
     * @DataTable\DefaultSort()
     */
    public $assessed;

    /**
     * @DataTable\Column(source="", name="Actions",  class="")
     * @DataTable\Format(dataFields={"id":"conditionsMeet.id", "customArgs": { "routeName": "edit_conditionsmeet"} }, template="AppBundle:DataTable:_dataTables_action_editcond.html.twig")
     */
    public $action;



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

        $conditionsMeetRepository = $this->em->getRepository('FormGeneratorBundle:ConditionsMeet');
        $qb = $conditionsMeetRepository->createQueryBuilder('conditionsMeet')
            ->leftJoin('conditionsMeet.assessed', 'aed')
            ->andWhere('aed.id = :assessedId')
            ->setParameter("assessedId", $assessed->getId());


        return $qb;
    }
}