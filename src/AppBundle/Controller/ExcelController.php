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

        list($headers, $data) = $em->getRepository('UserBundle:User')->getStrongPointsForExport();

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

        list($headers, $data) = $em->getRepository('UserBundle:User')->getUsersFunctionForExport();

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