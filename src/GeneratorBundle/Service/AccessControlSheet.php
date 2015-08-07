<?php

/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 08/07/2015
 * Time: 16:31.
 */

namespace GeneratorBundle\Service;

use Doctrine\ORM\EntityManager;
use GeneratorBundle\Entity\OpusSheet;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class AccessControlSheet
{
    private $securityTokenContext;
    private $em;

    public function __construct(TokenStorage $securityTokenContext, EntityManager $em)
    {
        $this->securityTokenContext = $securityTokenContext;
        $this->em = $em;
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

        $generatedStatus = $this->em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode('generee');
        $creationStatus = $this->em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode('creation');
        $director = $sheet->getDirector();
        $responsable = $sheet->getResponsable();

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

            //Si évaluateur connecté
            if ($userLogged->getId() == $opusSheet->getEvaluator()->getId()) {
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
