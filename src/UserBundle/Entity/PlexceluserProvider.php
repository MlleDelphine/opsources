<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 17/07/2015
 * Time: 17:02
 */

namespace UserBundle\Entity;


use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Arianespace\PlexcelBundle\Plexcel;
use UserBundle\Entity\User;

class PlexcelUserProvider implements UserProviderInterface
{
    private $plexcel;

    public function __construct(Plexcel $plexcel)
    {
        $this->plexcel = $plexcel;
    }

    public function loadUserByUsername($username)
    {
        $account = $this->plexcel->getAccount($username);

        if (!$account) {
            throw new UsernameNotFoundException();
        }

        $user    = new User();
        $user->setUsername($username);
        $user->setFullName($account["givenName"].' '.$account["sn"]);
        $user->setEmail($account["mail"]);



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