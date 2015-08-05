<?php

namespace UserBundle\Service;

use Arianespace\PlexcelBundle\Plexcel;
use Arianespace\PlexcelBundle\Security\User\UserManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

class UserPlexcelService implements UserManagerInterface
{
    public function createUser(Plexcel $plexcel)
    {
        $account = plexcel_get_account($plexcel);
    }

    public function updateUser(BaseUserInterface $user, Plexcel $plexcel)
    {
    }
}
