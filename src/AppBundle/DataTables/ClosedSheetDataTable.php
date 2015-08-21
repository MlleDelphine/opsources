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
 * @DataTable\Table(id="OpusSheetTable", displayLength=100)
 */
class ClosedSheetDataTable extends QueryBuilderDataTable implements QueryBuilderDataTableInterface
{
    /**
     * @var OpusUsers
     * @DataTable\Column(source="entity.evaluate", name="Utilisateur")
     * @DataTable\Format(dataFields={"fiche":"entity"}, template="AppBundle:Default/Includes/Datatables:_closed_sheet_datatable_name.html.twig")
     * @DataTable\DefaultSort()
     */
    public $evaluate;

    /**
     * @var \OpusSheetTemplate
     * @DataTable\Column(source="entity.opusTemplate.type.name", name="Type")
     */
    public $opusTemplate;

    /**
     * @var date
     * @DataTable\Column(source="entity.updatedAt", name="Date de fermeture")
     * @DataTable\Format(dataFields={"date":"entity.updatedAt"}, template="AppBundle:Default/Includes/Datatables:_datatable_date.html.twig")
     */
    public $updatedAt;

    // @DataTable\Format(dataFields={"date":"entity.updatedAt"}, template="AppBundle:Default/Includes/Datatables:_datatable_date.html.twig")

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
        $repository      = $this->em->getRepository('GeneratorBundle:OpusSheet');

        $qb = $repository->createQueryBuilder('sheet');

        $result = $qb
            ->join('sheet.status','status')
            ->where('status.intCode = :code1')
            ->orWhere('status.intCode = :code2')
            ->orWhere('status.intCode = :code3')
            ->setParameter('code1', -1)
            ->setParameter('code2', 8)
            ->setParameter('code3', 9);

        return $result;

    }
}