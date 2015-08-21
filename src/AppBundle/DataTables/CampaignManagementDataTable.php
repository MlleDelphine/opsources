<?php
/**
 * Created by PhpStorm.
 * User: Jordi
 * Date: 21/08/2015
 * Time: 09:51
 */

namespace AppBundle\DataTables;

use Brown298\DataTablesBundle\MetaData as DataTable;
use Brown298\DataTablesBundle\Model\DataTable\QueryBuilderDataTableInterface;
use Brown298\DataTablesBundle\Test\DataTable\QueryBuilderDataTable;
use Doctrine\ORM\EntityManager;
use mageekguy\atoum\asserters\dateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\EngineInterface;

/**
 * @DataTable\Table(id="OpusCampaignTable", displayLength=10)
 */
class CampaignManagementDataTable extends QueryBuilderDataTable implements QueryBuilderDataTableInterface
{
    /**
     * @var int
     * @DataTable\Column(source="entity.year", name="Année")
     * @DataTable\DefaultSort()
     */
    public $year;

    /**
     * @var datetime
     * @DataTable\Column(source="entity.mailDate", name="Mail Date")
     * @DataTable\Format(dataFields={"date":"entity.mailDate"}, template="AppBundle:Default/Includes/Datatables:_datatable_date.html.twig")
     */
    public $mailDate;

    /**
     * @var datetime
     * @DataTable\Column(source="entity.limitDate", name="Date limite")
     * @DataTable\Format(dataFields={"date":"entity.limitDate"}, template="AppBundle:Default/Includes/Datatables:_datatable_date.html.twig")
     */
    public $limitDate;

    /**
     * @var int
     * @DataTable\Column(source="entity.status", name="Statut")
     * * @DataTable\Format(dataFields={"status":"entity.status"}, template="AppBundle:Default/Includes/Datatables:_campaign_management_datatable_status.html.twig")
     */
    public $status;

    /**
     * @var \OpusSheetTemplate
     * @DataTable\Column(source="entity.opusTemplate.type.name", name="Type d'entretien")
     */
    public $opusTemplate;

    /**
     * @DataTable\Column(source="", name="Action")
     * @DataTable\Format(dataFields={"idCampaign":"entity.id"}, template="AppBundle:Default/Includes/Datatables:_campaign_management_datatable_action.html.twig")
     */
    public $action;


    /**
     * getQueryBuilder
     *
     * @param Request $request
     *
     * @return null
     */
    public function getQueryBuilder(Request $request = null)
    {
        /** @var $securityContext Symfony\Component\Security\Core\SecurityContext */
        $securityContext = $this->container->get('security.context');
        /** @var \UserBundle\Entity\User $user */
        $user            = $securityContext->getToken()->getUser();
        $repository      = $this->em->getRepository('GeneratorBundle:OpusCampaign');

        $qb = $repository->createQueryBuilder('campaign');

        return $qb;

    }
}