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
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\EngineInterface;

/**
 * @DataTable\Table(id="OpusSheetTable", displayLength=100)
 */
class ClosedSheetDataTable extends QueryBuilderDataTable implements QueryBuilderDataTableInterface
{
//    protected $container;
//    protected $em;
//
//    public function __construct(Container $container, EntityManager $em) {
//        $this->container = Container::;
//        $this->em = $em;
//    }

    /**
     * @var OpusUsers
     * @DataTable\Column(source="entity.evaluate.lastname", name="Nom")
     * @DataTable\DefaultSort()
     */
    public $evaluateLastname;

    /**
     * @var OpusUsers
     * @DataTable\Column(source="entity.evaluate.firstname", name="Prénom")
     * @DataTable\DefaultSort()
     */
    public $evaluateFirstname;

    /**
     * @var OpusUsers
     * @DataTable\Column(source="entity.status", name="Statut")
     * @DataTable\Format(dataFields={"status":"entity.status"}, template="AppBundle:Default/Includes/Datatables:_sheet_status_datatable.html.twig")
     */
    public $status;

    /**
     * @var \OpusSheetTemplate
     * @DataTable\Column(source="entity.opusTemplate.type.name", name="Type")
     * @DataTable\Format(dataFields={"type":"entity.opusTemplate.type.name"}, template="AppBundle:Default/Includes/Datatables:_sheet_type_datatable.html.twig")
     */
    public $type;

    /**
     * @var \OpusSheetTemplate
     * @DataTable\Column(source="entity.campaign.name", name="Campagne")
     */
    public $campaign;

    /**
     * @var \OpusSheetTemplate
     * @DataTable\Column(source="entity.opusTemplate.name", name="Template")
     */
    public $opusTemplate;

    /**
     * @var date
     * @DataTable\Column(source="entity.createdAt", name="Création")
     * @DataTable\Format(dataFields={"date":"entity.createdAt"}, template="AppBundle:Default/Includes/Datatables:_datatable_date.html.twig")
     */
    public $createdAt;

    /**
     * getQueryBuilder
     *
     * @param Request $request
     *
     * @return null
     */
    public function getQueryBuilder(Request $request = null)
    {
        $wheres = '';
        $defaultData = array('message' => 'Type here');
        $formSheet = $this->container->get('form.factory')->createBuilder('form', $defaultData)
            ->add(
                'search_lastname',
                'entity',
                array(
                    'class' => 'UserBundle:User',
                    'property' => 'lastName',
                    'expanded' => false,
                    'multiple' => false,
                    'label' => 'Nom',
                    'required' => false
                )
            )
            ->add(
                'search_firstname',
                'entity',
                array(
                    'class' => 'UserBundle:User',
                    'property' => 'firstName',
                    'expanded' => false,
                    'multiple' => false,
                    'label' => 'Prénom',
                    'required' => false
                )
            )
            ->add(
                'search_date',
                'choice',
                array(
                    'choices' => $this->container->get("doctrine.orm.entity_manager")->getRepository(
                        "GeneratorBundle:OpusSheet"
                    )->getAllDates(),
                    'expanded' => false,
                    'multiple' => false,
                    'label' => 'Année',
                    'required' => false
                )
            )
            ->add(
                'search_status',
                'entity',
                array(
                    'class' => 'GeneratorBundle:OpusSheetStatus',
                    'property' => 'label',
                    'expanded' => false,
                    'multiple' => false,
                    'label' => 'Statut',
                    'required' => false
                )
            )
            ->add(
                'search_type',
                'entity',
                array(
                    'class' => 'GeneratorBundle:OpusSheetType',
                    'property' => 'name',
                    'expanded' => false,
                    'multiple' => false,
                    'label' => 'Type d\'entretien',
                    'required' => false
                )
            )
            ->add('submit', 'submit', array('label' => "Filtrer"))
            ->getForm();


        if ($request->getMethod() == "POST") {
            $formSheet->submit($request);
            if ($formSheet->isValid()) {
                $postData = current($request->request->all());

                if ($postData["search_lastname"]) {
                    $user = $this->em->getRepository("UserBundle:User")->find($postData['search_lastname']);
                    $wheres .= "evaluate.lastName LIKE '" . $user->getLastName()."'";
                }
                if ($postData["search_firstname"]) {
                    $user = $this->em->getRepository("UserBundle:User")->find($postData['search_lastname']);
                    if ($wheres != '') {
                        $wheres .= " AND evaluate.firstName LIKE '" . $user->getFirstName()."'";
                    } else {
                        $wheres = "evaluate.firstName LIKE '" . $user->getFirstName()."'";
                    }
                }
                if ($postData["search_date"]) {
                    if ($wheres != '') {
                        $wheres .= " AND sheet.createdAt = " . $postData['search_date'];
                    } else {
                        $wheres = "sheet.createdAt = " . $postData['search_date'];
                    }
                }
                if ($postData["search_status"]) {
                    if ($wheres != '') {
                        $wheres .= " AND status.id = " . $postData['search_status'];
                    } else {
                        $wheres = "status.id = " . $postData['search_status'];
                    }
                }
                if ($postData["search_type"]) {
                    if ($wheres != '') {
                        $wheres .= " AND type.id = " . $postData['search_type'];
                    } else {
                        $wheres = "type.id = " . $postData['search_id'];
                    }
                }
            }
        }

        $repository = $this->em->getRepository('GeneratorBundle:OpusSheet');
        $qb = $repository->createQueryBuilder('sheet');

        if ($wheres) {
            $result = $qb
                ->join('sheet.evaluate', 'evaluate')
                ->join('sheet.opusTemplate', 'template')
                ->join('template.type', 'type')
                ->join('sheet.status', 'status')
                ->where($wheres)
                ->orderBy('evaluate.lastName ASC, evaluate.firstName ASC, sheet.createdAt DESC, type.name');
        }
        else{

            $result = $qb
                ->join('sheet.evaluate', 'evaluate')
                ->join('sheet.opusTemplate', 'template')
                ->join('template.type', 'type')
                ->join('sheet.status', 'status')
                ->orderBy('evaluate.lastName ASC, evaluate.firstName ASC, sheet.createdAt DESC, type.name');
        }

       // dump($result->getQuery()->getResult());
        return $result;

    }
}