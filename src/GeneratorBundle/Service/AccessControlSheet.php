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
//        $userConnected = $this->securityTokenContext->getToken()->getUser();
//        dump($sheet->getEvaluate());
//        dump($this->em->getRepository("UserBundle:User")->find(515));
//        die;
//
//        if ($sheet->getEvaluator() === $userConnected || $sheet->getEvaluate() === $userConnected || $sheet->getDirector() === $userConnected || $sheet->getResponsable() === $userConnected) {
//
//            return true;
//        }
        return true;
    }
}
