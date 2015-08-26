<?php
/**
 * Created by PhpStorm.
 * User: Jordi
 * Date: 14/08/2015
 * Time: 10:14
 */

namespace AppBundle\Controller;

use GeneratorBundle\Entity\OpusCampaign;
use GeneratorBundle\Entity\OpusSheet;
use GeneratorBundle\Form\Campaign\OpusCampaignType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

/**
 * Class DefaultController.
 */
class ExcelController extends Controller
{
    /**
     * @Route("/excel", defaults={"tableName" = null}, name="_excel")
     *
     * @return array
     */
    public function indexAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $title = 'title';
        $subject = 'subject';
        $description = 'description';
        $keyworks = 'keywords';
        $category = 'category';
        $filename = 'filename.xls';

        list($headers, $data) = $em->getRepository('UserBundle:User')->getUsersForExport();

        /**
         * @var \AppBundle\Service\ExcelBuilder $phpExcelService
         */
        $phpExcelService = $this->get("app.excelbuilder");
        $worksheet = $phpExcelService->createWorkSheet();

        $worksheet->getProperties()
            ->setCreator($user->getFullName())
            ->setLastModifiedBy($user->getFullName())
            ->setTitle($title)
            ->setSubject($subject)
            ->setDescription($description)
            ->setKeywords($keyworks)
            ->setCategory($category);

        $phpExcelService->draw($headers, $data, 0, true);

        return $phpExcelService->createAndGetResponse($filename);
    }

    /**
     * @Route("/generateexport/{templateID}/{exportID}/{exportValue}", defaults={"exportValue" = null}, name="_excel_exports")
     *
     * @param $templateID
     * @param $exportID
     * @param null $exportValue
     */
    public function getElementsForExportAction($templateID, $exportID, $exportValue){

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $template = $em->getRepository("GeneratorBundle:OpusSheetTemplate")->find($templateID);
        $templateFile = $template->getConfFile();
        $allAttributes = $this->get('app.customfields_parser')->parseYamlConf($templateFile, 'fields');
        //On le recherche avant tout dans les attributes
        $allAttributes['attr'] = array_values($allAttributes['attr']);
        $elementKey = array_search($exportID, array_column($allAttributes['attr'], 'id'));

        try {
            if ($elementKey) {
                $field = $allAttributes['attr'][$elementKey];
                $sheets = $em->getRepository("GeneratorBundle:OpusSheet")->findForAttributeExport($exportID, $exportValue);
                list($headers, $data) = $this->displayValuesForAttributeExport($sheets, $field);
            } else {
                //Si aucun attribut correspondant on le recherche dans les collections
                $allAttributes['collections'] = array_values($allAttributes['collections']);
                $elementKey = array_search($exportID, array_column($allAttributes['collections'], 'id'));
                $field = $allAttributes['collections'][$elementKey];
                $collections = $em->getRepository("GeneratorBundle:OpusCollection")->findForCollectionExport($exportID);
                list($headers, $data) = $this->displayValuesForCollectionExport($collections, $field);
            }
        }catch (NotFoundResourceException $e){
            throw $this->createNotFoundException('This export does not exist.');
        }

        /**
         * On génère le fichier Excel
         */
        $title = $field['export_desc'];
        $filename =  $this->wd_remove_accents($field["export_desc"]).' - '.date("Y-m-d H:i:s").'.xls';

        /**
         * @var \AppBundle\Service\ExcelBuilder $phpExcelService
         */
        $phpExcelService = $this->get("app.excelbuilder");
        $worksheet = $phpExcelService->createWorkSheet();

        $worksheet->getProperties()
            ->setCreator($user->getFullName())
            ->setLastModifiedBy($user->getFullName())
            ->setTitle($title);

        $phpExcelService->draw($headers, $data, 0, true);
//        $rowIterator = $phpExcelService->getRowIterator();
//        foreach($rowIterator as $k => $row) {
//            $cellIterator = $row->getCellIterator();
//            $rowIndex = $row->getRowIndex();
//            $array_data[$rowIndex] = array('A'=>'', 'B'=>'','C'=>'','D'=>'');
//            foreach ($cellIterator as $cell) {
//                if('A' == $cell->getColumn()) {
//                    $prevIndex = $rowIndex-1;
//                    if($cell->getCalculatedValue() == $phpExcelService->getCellByColumnAndRow(1,$prevIndex )->getCalculatedValue()){
//                        dump('coucou');
//                    }
//                }
//            }
//        }



        return $phpExcelService->createAndGetResponse($filename);
    }

    /**
     * Organise les données d'une fiche pour l'export (attribut simple)
     */
    public function displayValuesForAttributeExport($sheets, $attribute){

        $data = array();

        foreach($sheets AS $sheet){
            $line = array($sheet->getEvaluate()->getId(),$sheet->getEvaluate()->getFirstname(),$sheet->getEvaluate()->getLastname(), $attribute['export_value'] );
            array_push($data,$line);
        }

        $headers = array('ID','Prénom','Nom', $attribute['export_name']);

        return array($headers,$data);
    }
    /**
     * Organise les données d'une fiche pour l'export (collection d'attribut)
     * On passe des collections
     */
    public function displayValuesForCollectionExport($collections, $attribute){

        $data = array();
        $attrValues = array();

        foreach($collections AS $k => $coll){
            $collAttributes = $coll->getAttributes();
            foreach ($collAttributes as $collAttr) {

                if($collAttr->getValue() != null){
                    $attrValues[] = $collAttr->getValue();
                }elseif($collAttr->getValueDate() != null) {
                    $attrValues[] = $collAttr->getValueDate();
                }
                elseif($collAttr->getValueData() != null){
                    $attrValues[] = $collAttr->getValueData();
                }
                else{
                    $attrValues[] = "Valeur non renseignée";
                }
            }

            //Pour les attributs qui s'enregistrent à l'envers..
            if($collAttributes[0]->getLabel() != $attribute['child'][0]['id']){
                $attrValues = array_reverse($attrValues);
            }

            //On ajoute la ligne avec tous les attr sur la même ligne (ou une vide entre deux avant)

            if($k > 0 && $coll->getSheet()->getId() != $collections[$k-1]->getSheet()->getId()){
                $lineColor = array('', '', '', '', '', '', '');
                array_push($data,$lineColor);
            }

            $lineFirst = array($coll->getSheet()->getId(), $coll->getSheet()->getEvaluate()->getId(),$coll->getSheet()->getEvaluate()->getFirstname(), $coll->getSheet()->getEvaluate()->getLastname());
            $line = array_merge($lineFirst, $attrValues);
            array_push($data,$line);
            unset($attrValues);
        }

        $headers = array('ID de la fiche', 'ID utilisateur','Prénom','Nom');

        foreach($attribute['child'] as $confAttr){
            $headers[] = $confAttr['conf']['label'];
        }

        return array($headers,$data);

    }


    private function wd_remove_accents($str, $charset='utf-8')
    {
        $str = htmlentities($str, ENT_NOQUOTES, $charset);

        $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
        $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères

        return $str;
    }

    /**
     * @Route("/excel/objectives", defaults={"tableName" = null}, name="_excel_objectives")
     *
     * Liste des objectifs fixés de vos collaborateurs
     *
     * @return array
     */
    public function objectivesAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $title = 'Liste des objectifs fixés de vos collaborateurs';
        $filename = 'Liste des objectifs fixes de vos collaborateurs.xls';

        list($headers, $data) = $em->getRepository('UserBundle:User')->getUsersForExport();

        /**
         * @var \AppBundle\Service\ExcelBuilder $phpExcelService
         */
        $phpExcelService = $this->get("app.excelbuilder");
        $worksheet = $phpExcelService->createWorkSheet();

        $worksheet->getProperties()
            ->setCreator($user->getFullName())
            ->setLastModifiedBy($user->getFullName())
            ->setTitle($title);



        $phpExcelService->draw($headers, $data, 0, true);



        return $phpExcelService->createAndGetResponse($filename);
    }

    /**
     * @Route("/excel/business", defaults={"tableName" = null}, name="_excel_business")
     *
     * Liste des métier 1 et métier 2 par salarié
     *
     * @return array
     */
    public function businessAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $title = 'Liste des métier 1 et métier 2 par salarie';
        $filename = 'Liste des metier 1 et metier 2 par salarie.xls';

        list($headers, $data) = $em->getRepository('UserBundle:User')->getJobsByUserForExport();

        /**
         * @var \AppBundle\Service\ExcelBuilder $phpExcelService
         */
        $phpExcelService = $this->get("app.excelbuilder");
        $worksheet = $phpExcelService->createWorkSheet();

        $worksheet->getProperties()
            ->setCreator($user->getFullName())
            ->setLastModifiedBy($user->getFullName())
            ->setTitle($title);

        $phpExcelService->draw($headers, $data, 0, true);

        return $phpExcelService->createAndGetResponse($filename);
    }

    /**
     * @Route("/excel/skills-to-be-developed-by-collaborator", defaults={"tableName" = null}, name="_excel_skills_to_be_developed_by_collaborator")
     *
     * Compétences à développer de vos collaborateurs
     *
     * @return array
     */
    public function skillsToBeDevelopedByCollaboratorAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $title = 'Compétences à développer de vos collaborateurs';
        $filename = 'Competences a developper de vos collaborateurs.xls';

        list($headers, $data) = $em->getRepository('UserBundle:User')->getSkillsToBeDevelopedByCollaboratorForExport();

        /**
         * @var \AppBundle\Service\ExcelBuilder $phpExcelService
         */
        $phpExcelService = $this->get("app.excelbuilder");
        $worksheet = $phpExcelService->createWorkSheet();

        $worksheet->getProperties()
            ->setCreator($user->getFullName())
            ->setLastModifiedBy($user->getFullName())
            ->setTitle($title);

        $phpExcelService->draw($headers, $data, 0, true);

        return $phpExcelService->createAndGetResponse($filename);
    }

    /**
     * @Route("/excel/training-plan-by-collaborator", defaults={"tableName" = null}, name="_excel_training_plan_by_collaborator")
     *
     * Plan formation souhaité de vos collaborateurs
     *
     * @return array
     */
    public function trainingPlanByCollaboratorAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $title = 'Plan formation souhaité de vos collaborateurs';
        $filename = 'Plan formation souhaite de vos collaborateurs.xls';

        list($headers, $data) = $em->getRepository('UserBundle:User')->getTrainingPlanByCollaboratorForExport();

        /**
         * @var \AppBundle\Service\ExcelBuilder $phpExcelService
         */
        $phpExcelService = $this->get("app.excelbuilder");
        $worksheet = $phpExcelService->createWorkSheet();

        $worksheet->getProperties()
            ->setCreator($user->getFullName())
            ->setLastModifiedBy($user->getFullName())
            ->setTitle($title);

        $phpExcelService->draw($headers, $data, 0, true);

        return $phpExcelService->createAndGetResponse($filename);
    }

    /**
     * @Route("/excel/statistics", defaults={"tableName" = null}, name="_excel_statistics")
     *
     * Statistiques sur les fiches par département et par évaluation
     *
     * @return array
     */
    public function statisticsPlanAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $title = 'Statistiques sur les fiches par département et par évaluation';
        $filename = 'Statistiques sur les fiches par departement et par evaluation.xls';

        list($headers, $data) = $em->getRepository('UserBundle:User')->getStatisticsForExport();

        /**
         * @var \AppBundle\Service\ExcelBuilder $phpExcelService
         */
        $phpExcelService = $this->get("app.excelbuilder");
        $worksheet = $phpExcelService->createWorkSheet();

        $worksheet->getProperties()
            ->setCreator($user->getFullName())
            ->setLastModifiedBy($user->getFullName())
            ->setTitle($title);

        $phpExcelService->draw($headers, $data, 0, true);

        return $phpExcelService->createAndGetResponse($filename);
    }

    /**
     * @Route("/excel/professional-meeting", defaults={"tableName" = null}, name="_excel_professional_meeting")
     *
     * Liste des personnes qui ont demandé un entretien professionnel
     *
     * @return array
     */
    public function professionalMeetingAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $title = 'Liste des personnes qui ont demandé un entretien professionnel';
        $filename = 'Liste des personnes qui ont demande un entretien professionnel.xls';

        list($headers, $data) = $em->getRepository('UserBundle:User')->getProfessionalMeetingForExport();

        /**
         * @var \AppBundle\Service\ExcelBuilder $phpExcelService
         */
        $phpExcelService = $this->get("app.excelbuilder");
        $worksheet = $phpExcelService->createWorkSheet();

        $worksheet->getProperties()
            ->setCreator($user->getFullName())
            ->setLastModifiedBy($user->getFullName())
            ->setTitle($title);

        $phpExcelService->draw($headers, $data, 0, true);

        return $phpExcelService->createAndGetResponse($filename);
    }

    /**
     * @Route("/excel/rh-meeting", defaults={"tableName" = null}, name="_excel_rh_meeting")
     *
     * Liste des personnes qui ont demandé un entretien RH
     *
     * @return array
     */
    public function rhMeetingAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $title = 'Liste des personnes qui ont demandé un entretien RH';
        $filename = 'Liste des personnes qui ont demande un entretien RH.xls';

        list($headers, $data) = $em->getRepository('UserBundle:User')->getRhMeetingForExport();

        /**
         * @var \AppBundle\Service\ExcelBuilder $phpExcelService
         */
        $phpExcelService = $this->get("app.excelbuilder");
        $worksheet = $phpExcelService->createWorkSheet();

        $worksheet->getProperties()
            ->setCreator($user->getFullName())
            ->setLastModifiedBy($user->getFullName())
            ->setTitle($title);

        $phpExcelService->draw($headers, $data, 0, true);

        return $phpExcelService->createAndGetResponse($filename);
    }

    /**
     * @Route("/excel/evolution-wishes", defaults={"tableName" = null}, name="_excel_evolution_wishes")
     *
     * Liste des souhaits d'évolution des salariés
     *
     * @return array
     */
    public function evolutionWishesAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $title = "Liste des souhaits d'évolution des salariés";
        $filename = "Liste des souhaits d'evolution des salaries.xls";

        list($headers, $data) = $em->getRepository('UserBundle:User')->getEvolutionWishesForExport();

        /**
         * @var \AppBundle\Service\ExcelBuilder $phpExcelService
         */
        $phpExcelService = $this->get("app.excelbuilder");
        $worksheet = $phpExcelService->createWorkSheet();

        $worksheet->getProperties()
            ->setCreator($user->getFullName())
            ->setLastModifiedBy($user->getFullName())
            ->setTitle($title);

        $phpExcelService->draw($headers, $data, 0, true);

        return $phpExcelService->createAndGetResponse($filename);
    }

    /**
     * @Route("/excel/skills-to-be-developed-by-employe", defaults={"tableName" = null}, name="_excel_skills_to_be_developed_by_employe")
     *
     * Liste des compétences à développer par salarié
     *
     * @return array
     */
    public function skillsToBeDevelopedByEmployeAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $title = 'Liste des compétences à développer par salarié';
        $filename = 'Liste des competences a developper par salarie.xls';

        list($headers, $data) = $em->getRepository('UserBundle:User')->getSkillsToBeDevelopedByEmployeForExport();

        /**
         * @var \AppBundle\Service\ExcelBuilder $phpExcelService
         */
        $phpExcelService = $this->get("app.excelbuilder");
        $worksheet = $phpExcelService->createWorkSheet();

        $worksheet->getProperties()
            ->setCreator($user->getFullName())
            ->setLastModifiedBy($user->getFullName())
            ->setTitle($title);

        $phpExcelService->draw($headers, $data, 0, true);

        return $phpExcelService->createAndGetResponse($filename);
    }

    /**
     * @Route("/excel/internal-communication", defaults={"tableName" = null}, name="_excel_internal_communication")
     *
     * Avis sur la communication interne
     *
     * @return array
     */
    public function internalCommunicationAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $title = 'Avis sur la communication interne';
        $filename = 'Avis sur la communication interne.xls';

        list($headers, $data) = $em->getRepository('UserBundle:User')->getInternalCommunicationForExport();

        /**
         * @var \AppBundle\Service\ExcelBuilder $phpExcelService
         */
        $phpExcelService = $this->get("app.excelbuilder");
        $worksheet = $phpExcelService->createWorkSheet();

        $worksheet->getProperties()
            ->setCreator($user->getFullName())
            ->setLastModifiedBy($user->getFullName())
            ->setTitle($title);

        $phpExcelService->draw($headers, $data, 0, true);

        return $phpExcelService->createAndGetResponse($filename);
    }

    /**
     * @Route("/excel/mobility", defaults={"tableName" = null}, name="_excel_mobility")
     *
     * Liste des personnes mobiles géographiquement
     *
     * @return array
     */
    public function mobilityAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $title = 'Liste des personnes mobiles géographiquement';
        $filename = 'Liste des personnes mobiles geographiquement.xls';

        list($headers, $data) = $em->getRepository('GeneratorBundle:OpusSheet')->getMobilityForExport();

        /**
         * @var \AppBundle\Service\ExcelBuilder $phpExcelService
         */
        $phpExcelService = $this->get("app.excelbuilder");
        $worksheet = $phpExcelService->createWorkSheet();

        $worksheet->getProperties()
            ->setCreator($user->getFullName())
            ->setLastModifiedBy($user->getFullName())
            ->setTitle($title);

        $phpExcelService->draw($headers, $data, 0, true);

        return $phpExcelService->createAndGetResponse($filename);
    }

    /**
     * @Route("/excel/change-of-post", defaults={"tableName" = null}, name="_excel_change_of_post")
     *
     * Liste des personnes ayant changé de poste
     *
     * @return array
     */
    public function changeOfPostAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $title = 'Liste des personnes ayant changé de poste';
        $filename = 'Liste des personnes ayant change de poste.xls';

        list($headers, $data) = $em->getRepository('UserBundle:User')->getInternalCommunicationForExport();

        /**
         * @var \AppBundle\Service\ExcelBuilder $phpExcelService
         */
        $phpExcelService = $this->get("app.excelbuilder");
        $worksheet = $phpExcelService->createWorkSheet();

        $worksheet->getProperties()
            ->setCreator($user->getFullName())
            ->setLastModifiedBy($user->getFullName())
            ->setTitle($title);

        $phpExcelService->draw($headers, $data, 0, true);

        return $phpExcelService->createAndGetResponse($filename);
    }

    /**
     * @Route("/excel/training-plan-by-employe", defaults={"tableName" = null}, name="_excel_training_plan_by_employe")
     *
     * Plan formation souhaité par salarié
     *
     * @return array
     */
    public function trainingPlanByEmployeAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $title = 'Plan formation souhaité par salarié';
        $filename = 'Plan formation souhaite par salarie.xls';

        list($headers, $data) = $em->getRepository('UserBundle:User')->getTrainingPlanByEmployeForExport();

        /**
         * @var \AppBundle\Service\ExcelBuilder $phpExcelService
         */
        $phpExcelService = $this->get("app.excelbuilder");
        $worksheet = $phpExcelService->createWorkSheet();

        $worksheet->getProperties()
            ->setCreator($user->getFullName())
            ->setLastModifiedBy($user->getFullName())
            ->setTitle($title);

        $phpExcelService->draw($headers, $data, 0, true);

        return $phpExcelService->createAndGetResponse($filename);
    }

    /**
     * @Route("/excel/topics-for-discussion", defaults={"tableName" = null}, name="_excel_topics_for_discussion")
     *
     * Autres thèmes de discussion (rémunération, organisation, conditions et charge de travail, articulation entre vie professionnelle et vie personnelle)
     *
     * @return array
     */
    public function topicsForDiscussionAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $title = 'Thèmes de discussion';
        $filename = 'Themes de discussion.xls';

        list($headers, $data) = $em->getRepository('UserBundle:User')->getTopicsForDiscussionForExport();

        /**
         * @var \AppBundle\Service\ExcelBuilder $phpExcelService
         */
        $phpExcelService = $this->get("app.excelbuilder");
        $worksheet = $phpExcelService->createWorkSheet();

        $worksheet->getProperties()
            ->setCreator($user->getFullName())
            ->setLastModifiedBy($user->getFullName())
            ->setTitle($title);

        $phpExcelService->draw($headers, $data, 0, true);

        return $phpExcelService->createAndGetResponse($filename);
    }

    /**
     * @Route("/excel/observations", defaults={"tableName" = null}, name="_excel_observations")
     *
     * Observations éventuelles du salarié à l'issue de l'entretien
     *
     * @return array
     */
    public function observationsAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $title = "Observations éventuelles du salarié à l'issue de l'entretien";
        $filename = "Observations eventuelles du salarie a l'issue de l'entretien.xls";

        list($headers, $data) = $em->getRepository('UserBundle:User')->getObservationsForExport();

        /**
         * @var \AppBundle\Service\ExcelBuilder $phpExcelService
         */
        $phpExcelService = $this->get("app.excelbuilder");
        $worksheet = $phpExcelService->createWorkSheet();

        $worksheet->getProperties()
            ->setCreator($user->getFullName())
            ->setLastModifiedBy($user->getFullName())
            ->setTitle($title);

        $phpExcelService->draw($headers, $data, 0, true);

        return $phpExcelService->createAndGetResponse($filename);
    }

    /**
     * @Route("/excel/strong-points", defaults={"tableName" = null}, name="_excel_strong_points")
     *
     * Liste des points forts des salariés, Aptitudes, Savoirs / Savoir Faire
     *
     * @return array
     */
    public function strongPointsAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $title = "Liste des points forts des salariés";
        $filename = "Liste des points forts des salaries.xls";

        list($headers, $data) = $em->getRepository('GeneratorBundle:OpusSheet')->getStrongPointsForExport();

        /**
         * @var \AppBundle\Service\ExcelBuilder $phpExcelService
         */
        $phpExcelService = $this->get("app.excelbuilder");
        $worksheet = $phpExcelService->createWorkSheet();

        $worksheet->getProperties()
            ->setCreator($user->getFullName())
            ->setLastModifiedBy($user->getFullName())
            ->setTitle($title);

        $phpExcelService->draw($headers, $data, 0, true);

        return $phpExcelService->createAndGetResponse($filename);
    }

    /**
     * @Route("/excel/training-received", defaults={"tableName" = null}, name="_excel_training_received")
     *
     * Liste des formations reçues (formations professionnelles)
     *
     * @return array
     */
    public function trainingReceivedAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $title = "Liste des formations professionnelles reçues";
        $filename = "Liste des formations professionnelles recues.xls";

        list($headers, $data) = $em->getRepository('UserBundle:User')->getTrainingReceivedForExport();

        /**
         * @var \AppBundle\Service\ExcelBuilder $phpExcelService
         */
        $phpExcelService = $this->get("app.excelbuilder");
        $worksheet = $phpExcelService->createWorkSheet();

        $worksheet->getProperties()
            ->setCreator($user->getFullName())
            ->setLastModifiedBy($user->getFullName())
            ->setTitle($title);

        $phpExcelService->draw($headers, $data, 0, true);

        return $phpExcelService->createAndGetResponse($filename);
    }

    /**
     * @Route("/excel/campaign-function", defaults={"tableName" = null}, name="_excel_campaign_function")
     *
     * Liste des fonctions campagnes pour chaque utilisateur
     *
     * @return array
     */
    public function campaignFunctionAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $title = "Liste des fonctions campagnes pour chaque utilisateur";
        $filename = "Liste des fonctions campagnes pour chaque utilisateur.xls";

        list($headers, $data) = $em->getRepository('UserBundle:User')->getCampaignFunctionForExport();

        /**
         * @var \AppBundle\Service\ExcelBuilder $phpExcelService
         */
        $phpExcelService = $this->get("app.excelbuilder");
        $worksheet = $phpExcelService->createWorkSheet();

        $worksheet->getProperties()
            ->setCreator($user->getFullName())
            ->setLastModifiedBy($user->getFullName())
            ->setTitle($title);

        $phpExcelService->draw($headers, $data, 0, true);

        return $phpExcelService->createAndGetResponse($filename);
    }

    /**
     * @Route("/excel/users-function", defaults={"tableName" = null}, name="_excel_users_function")
     *
     * Liste de la fonction de chaque utilisateur
     *
     * @return array
     */
    public function usersFunctionAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $title = "Liste de la fonction de chaque utilisateur";
        $filename = "Liste de la fonction de chaque utilisateur.xls";

        list($headers, $data) = $em->getRepository('GeneratorBundle:OpusSheet')->getUsersFunctionForExport();

        /**
         * @var \AppBundle\Service\ExcelBuilder $phpExcelService
         */
        $phpExcelService = $this->get("app.excelbuilder");
        $worksheet = $phpExcelService->createWorkSheet();

        $worksheet->getProperties()
            ->setCreator($user->getFullName())
            ->setLastModifiedBy($user->getFullName())
            ->setTitle($title);

        $phpExcelService->draw($headers, $data, 0, true);

        return $phpExcelService->createAndGetResponse($filename);
    }

    /**
     * @Route("/excel/functional-manager", defaults={"tableName" = null}, name="_excel_functional_manager")
     *
     * Liste de tous les managers fonctionnels ainsi que des personnes qu'ils supervisent
     *
     * @return array
     */
    public function functionalManagerAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $title = "Liste de tous les managers fonctionnels ainsi que des personnes qu'ils supervisent";
        $filename = "Liste de tous les managers fonctionnels ainsi que des personnes qu'ils supervisent.xls";

        list($headers, $data) = $em->getRepository('UserBundle:User')->getFunctionalManagerForExport();

        /**
         * @var \AppBundle\Service\ExcelBuilder $phpExcelService
         */
        $phpExcelService = $this->get("app.excelbuilder");
        $worksheet = $phpExcelService->createWorkSheet();

        $worksheet->getProperties()
            ->setCreator($user->getFullName())
            ->setLastModifiedBy($user->getFullName())
            ->setTitle($title);

        $phpExcelService->draw($headers, $data, 0, true);

        return $phpExcelService->createAndGetResponse($filename);
    }

}