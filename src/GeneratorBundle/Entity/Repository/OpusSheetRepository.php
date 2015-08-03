<?php

namespace GeneratorBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use GeneratorBundle\Entity\OpusCampaign;
use UserBundle\Entity\User;

/**
 * OpusSheetTypeRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OpusSheetRepository extends EntityRepository
{
    /**
     * Retourne les fiches en cours d'édition pour une campagne et un utilisateur donnés.
     *
     * @param OpusCampaign $campaign
     * @param User         $user
     *
     * @return int
     */
    public function findSheetsNotClosedInCampaign(OpusCampaign $campaign, User $user)
    {
        $qb = $this->createQueryBuilder('sh');
        $result = $qb->join('sh.status', 's')
            ->join('sh.campaign', 'c')
            ->join('sh.evaluate', 'e')
            ->where($qb->expr()->notLike('s.strCode', 'close'))
            ->andWhere($qb->expr()->notLike('s.strCode', 'close_finish'))
            ->where('c.id = :campaignId')
            ->andWhere('e.id = :userId')
            ->setParameters(array(
                'campaignId' => $campaign->getId(),
                'userId' => $user->getId()))
            ->orderBy('sh.id', 'DESC')
            ->getQuery()
            ->setMaxResults(1)
            ->getResult();

        return $result;
    }
}