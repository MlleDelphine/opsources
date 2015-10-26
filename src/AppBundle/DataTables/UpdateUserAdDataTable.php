<?php
/**
 * Created by PhpStorm.
 * User: Jordi
 * Date: 07/09/2015
 * Time: 10:37
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
 * @DataTable\Table(id="UpdateUserAdDataTable", displayLength=10)
 */
class UpdateUserAdDataTable extends QueryBuilderDataTable implements QueryBuilderDataTableInterface
{

    /**
     * @var int
     * @DataTable\Column(source="user.id", name="ID")
     * @DataTable\DefaultSort()
     */
    public $id;

    /**
     * @var string
     * @DataTable\Column(source="user.lastName", name="Nom")
     * @DataTable\DefaultSort()
     */
    public $lastName;

    /**
     * @var string
     * @DataTable\Column(source="user.firstName", name="Prénom")
     * @DataTable\DefaultSort()
     */
    public $firstName;

    /**
     * @DataTable\Column(source="", name="Action")
     * @DataTable\Format(dataFields={"entity":"entity"}, template="AppBundle:Default/Includes/Datatables:_update_user_datatable_action.html.twig")
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
        $repository = $this->em->getRepository('UserBundle:User');
        $qb = $repository->createQueryBuilder('user');
        return $qb;

    }
}