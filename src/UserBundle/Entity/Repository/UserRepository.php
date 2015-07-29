<?php

namespace UserBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use UserBundle\Entity\User;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    public function findAllExcept(User $user) {

        $qb = $this->createQueryBuilder('u')
            ->where('u.id <> :uid')
            ->setParameter(':uid', $user->getId());

        return $qb;
    }

    public function findOneByQuery(User $user) {

        $qb = $this->createQueryBuilder('u')
            ->where('u.id = :uid')
            ->setParameter(':uid', $user->getId());

        return $qb;
    }
}