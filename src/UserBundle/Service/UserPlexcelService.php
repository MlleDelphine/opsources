<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 29/06/2015
 * Time: 14:31
 */

namespace UserBundle\Service;

use Arianespace\PlexcelBundle\Plexcel;
use Arianespace\PlexcelBundle\Security\User\UserManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;


class UserPlexcelService implements UserManagerInterface {

    public function createUser(Plexcel $plexcel){

    }

    public function updateUser(BaseUserInterface $user, Plexcel $plexcel){

    }

}