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
 * @DataTable\Table(id="professionalMeetAssessedTable")
 *
 *
 */
class ProfessionalMeetAssessedTable extends QueryBuilderDataTable implements QueryBuilderDataTableInterface{

    /**
     * @var datetime
     * @DataTable\Column(source="professionalMeet.meetDate", name="Date",  class="text-center")
     * @DataTable\Column(sortable=true)
     * @DataTable\Format(dataFields={"dateAttribute":"professionalMeet.meetDate"}, template="AppBundle:DataTable:_date_template.html.twig")
     */
    public $meetDate;

    /**
     * @var int
     * @DataTable\Column(source="professionalMeet.id", name="ID", class="")
     * @DataTable\Column(sortable=true)
     */
    public $id;

    /**
     * @var string
     * @DataTable\Column(source="professionalMeet.name", name="Intitulé",  class="text-center")
     * @DataTable\Column(sortable=true)
     * @DataTable\DefaultSort()
     */
    public $name;

    /**
     * @var string
     * @DataTable\Column(source="professionalMeet.assessor", name="Evaluateur",  class="text-center")
     * @DataTable\Column(sortable=true)
     * @DataTable\Format(dataFields={"person":"professionalMeet.assessor"}, template="AppBundle:DataTable:_person_template.html.twig")
     * @DataTable\DefaultSort()
     */
    public $assessor;

    /**
     * @var string
     * @DataTable\Column(source="professionalMeet.assessed", name="Evalué",  class="text-center")
     * @DataTable\Column(sortable=true)
     * @DataTable\Format(dataFields={"person":"professionalMeet.assessed"}, template="AppBundle:DataTable:_person_template.html.twig")
     * @DataTable\DefaultSort()
     */
    public $assessed;

    /**
     * @DataTable\Column(source="", name="Actions",  class="")
     * @DataTable\Format(dataFields={"id":"professionalMeet.id", "customRouteName": "edit_professionalmeet" }, template="AppBundle:DataTable:_dataTables_action.html.twig")
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

        $professionalMeetRepository = $this->em->getRepository('FormGeneratorBundle:ProfessionalMeet');
        $qb = $professionalMeetRepository->createQueryBuilder('professionalMeet')
            ->leftJoin('professionalMeet.assessed', 'aed')
            ->andWhere('aed.id = :assessedId')
            ->setParameter("assessedId", $assessed->getId());


        return $qb;
    }
}