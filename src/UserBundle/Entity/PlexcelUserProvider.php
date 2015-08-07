<?php

/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 17/07/2015
 * Time: 17:02.
 */

namespace UserBundle\Entity;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Arianespace\PlexcelBundle\Plexcel;
use GeneratorBundle\Service\CustomFieldsParser;

class PlexcelUserProvider implements UserProviderInterface
{
    private $plexcel;
    private $entityManager = null;
    private $service;

    public function __construct(Plexcel $plexcel, EntityManager $manager, CustomFieldsParser $service)
    {
        $this->plexcel = $plexcel;
        $this->entityManager = $manager;
        $this->service = $service;
    }

    public function loadUserByUsername($username)
    {

        $account = $this->plexcel->getAccount($username);

        $exist = $this->entityManager->getRepository('UserBundle:User')->findOneBy(array('login' => $account['sAMAccountName']));

        if (!$account) {
            throw new UsernameNotFoundException();
        } elseif (!$exist) {
            throw new UsernameNotFoundException();
        }

        $user = $exist;

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    public function supportsClass($class)
    {
        return $class instanceof User;
    }
}
