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
 * @DataTable\Table(id="professionalMeetAssessorTable")
 */
class ProfessionalMeetAssessorTable extends QueryBuilderDataTable implements QueryBuilderDataTableInterface
{
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
     * @var string
     * @DataTable\Column(source="professionalMeet.status.name", name="Statut",  class="text-center")
     * @DataTable\Column(sortable=true)
     * @DataTable\DefaultSort()
     */
    public $status;

    /**
     * @DataTable\Column(source="", name="Actions",  class="")
     * @DataTable\Format(dataFields={"id":"professionalMeet.id"}, template="AppBundle:DataTable:_dataTables_action_editpro.html.twig")
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
        $professionalMeetRepository = $this->em->getRepository('FormGeneratorBundle:ProfessionalMeet');
        $qb = $professionalMeetRepository->createQueryBuilder('professionalMeet')
            ->leftJoin('professionalMeet.assessor', 'aor')
            ->andWhere('aor.id = :assessorId')
            ->setParameter('assessorId', $assessor->getId());

        return $qb;
    }
}
