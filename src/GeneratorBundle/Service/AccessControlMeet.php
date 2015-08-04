<?php

/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 08/07/2015
 * Time: 16:31.
 */

namespace GeneratorBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class AccessControlMeet
{
    private $securityTokenContext;
    private $em;

    public function __construct(TokenStorage $securityTokenContext, EntityManager $em)
    {
        $this->securityTokenContext = $securityTokenContext;
        $this->em = $em;
    }

    public function canAccess($meet)
    {
        $userConnected = $this->securityTokenContext->getToken()->getUser();

        /*
         * Si c'est le manager il peut éditer :
         * - pending_m
         * - refused_e
         * - validated_e
         *
         * Si c'est l'évalué il peut éditer :
         * - validated_m
         * - refused_m
         */
        if ($meet->getAssessor() === $userConnected) {
            $pending = $this->em->getRepository('FormGeneratorBundle:Status')->findOneBy(array('code' => 'pending_m'));
            $refusedE = $this->em->getRepository('FormGeneratorBundle:Status')->findOneBy(array('code' => 'refused_e'));
            $validatedE = $this->em->getRepository('FormGeneratorBundle:Status')->findOneBy(array('code' => 'validated_e'));

            if ($meet->getStatus() === $pending || $meet->getStatus() === $refusedE || $meet->getStatus() === $validatedE) {
                return true;
            }

            return false;
        } elseif ($meet->getAssessed() === $userConnected) {
            $validatedM = $this->em->getRepository('FormGeneratorBundle:Status')->findOneBy(array('code' => 'validated_m'));
            $refusedM = $this->em->getRepository('FormGeneratorBundle:Status')->findOneBy(array('code' => 'refused_m'));

            if ($meet->getStatus() === $validatedM || $meet->getStatus() === $refusedM) {
                return true;
            }

            return false;
        } else {
            return false;
        }
    }
}
