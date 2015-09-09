<?php

/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 08/07/2015
 * Time: 16:31.
 */

namespace GeneratorBundle\Service;

use AppBundle\Service\CheckRoleService;
use Doctrine\ORM\EntityManager;
use GeneratorBundle\Entity\OpusSheet;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class AccessControlSheet
{
    private $securityTokenContext;
    private $em;
    private $checkRole;

    public function __construct(TokenStorage $securityTokenContext, EntityManager $em, CheckRoleService $checkRole)
    {
        $this->securityTokenContext = $securityTokenContext;
        $this->em = $em;
        $this->checkRole = $checkRole;
    }

    /**
     * La personne connectée actuellement peut elle accéder à la fiche ? (Manque sûrement " a t-elle le rôle DRH ou ADMIN ?"
     *
     * @param OpusSheet $sheet
     * @return bool
     */
    public function canAccess(OpusSheet $sheet)
    {
        $userConnected = $this->securityTokenContext->getToken()->getUser();
        $userConnected = $this->em->getRepository('UserBundle:User')->find($userConnected->getId());

        $generatedStatus = $this->em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode('generee');
        $creationStatus = $this->em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode('creation');
        $director = $sheet->getDirector();
        $responsable = $sheet->getResponsable();

        if($this->checkRole->isGranted('ROLE_ADMIN', $userConnected)){
            return true;
        }

        if ($sheet->getEvaluator()->getId() === $userConnected->getId()
            || ($sheet->getEvaluate()->getId() === $userConnected->getId() && $sheet->getStatus() != $creationStatus)
            || ( isset($director) && $sheet->getDirector()->getId() === $userConnected->getId())
            || (  isset($responsable) && $sheet->getResponsable()->getId() === $userConnected ->getId())) {

            return true;
        }
        return false;
    }

    /**
     * Détermine quel champs seront modifiables en fonction de la personne connectée et du statut de la fiche
     * @param OpusSheet $opusSheet
     */
    public function determineWriteRight(OpusSheet $opusSheet = null){

        if($opusSheet) {

            $userLogged = $this->securityTokenContext->getToken()->getUser();
            $userLogged = $this->em->getRepository('UserBundle:User')->find($userLogged->getId());
            $sheetStatus = $opusSheet->getStatus();

            $generatedStatus = $this->em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode('generee');
            $creationStatus = $this->em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode('creation');
            $vEvaluatedStatus = $this->em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode(
                'valid_evaluated'
            );
            $vEvaluatorStatus = $this->em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode(
                'valid_evaluator'
            );
            $vFinalEvaluatorStatus = $this->em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode(
                'valid_final_evaluator'
            );
            $vRHStatus = $this->em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode(
                'valid_RH'
            );


            if( ( $opusSheet->getStatus() == $vRHStatus && $this->checkRole->isGranted('ROLE_RH', $userLogged)) || $this->checkRole->isGranted('ROLE_ADMIN', $userLogged)){
                return "drh_decision";
            }
            //Si évaluateur connecté
            if ($userLogged->getId() == $opusSheet->getEvaluator()->getId() ) {
                if ($sheetStatus == $generatedStatus || $sheetStatus == $creationStatus || $sheetStatus == $vEvaluatorStatus ) {
                    //L'évaluateur peut modifier ses champs mais pas ceux du user
                    return 'evaluator_write';
                } else {
                    //L'évaluateur ne peut modifier aucun champ
                    return 'none';
                }
            } elseif ($userLogged->getId() == $opusSheet->getEvaluate()->getId()) {
                if ($sheetStatus == $vEvaluatedStatus) {
                    //L'évalué peut modifier ses propres champs si c'est à son tour
                    return 'evaluate_write';
                } else {
                    //L'évalué ne peut rien modifier
                    return 'none';
                }
            } else {
                return 'none';
            }
        }
        else{
            return 'none';
        }

    }
}
