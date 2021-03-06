<?php

/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 17/07/2015
 * Time: 15:16.
 */

namespace UserBundle\Entity;

use Arianespace\PlexcelBundle\Plexcel;
use Arianespace\PlexcelBundle\Security\User\UserInterface;
use Arianespace\PlexcelBundle\Security\User\UserManagerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

class UserManager implements UserManagerInterface
{
    private $entityManager = null;
    private $groupsConfig = array();
    private $container;

    public function __construct(EntityManager $manager, $groupsConfig, Container $container)
    {
        $this->entityManager = $manager;
        $this->groupsConfig = $groupsConfig;
        $this->container = $container;
    }

    /**
     * Create a new user with plexcel data.
     *
     * @param Plexcel $plexcel Plexcel
     *
     * @return UserInterface
     */
    public function createUser(Plexcel $plexcel)
    {
        $plexcelAccount = $plexcel->getAccount();
        return $this->container->get('ldap_user_service')->updateByLogin($plexcelAccount['sAMAccountName']);
        // TODO: Implement createUser() method.
    }

    /**
     * Update a user with plexcel data.
     *
     * @param UserInterface $user    User
     * @param Plexcel       $plexcel Plexcel
     */
    public function updateUser(BaseUserInterface $user, Plexcel $plexcel)
    {
        if (!$user instanceof User) {
            return $user;
        }

        $user = $this->fetchSids($user, $plexcel);

        //On met à jour via l'AD pour les rôles notamment
        $user->setRoles(array());
        $user = $this->container->get("ldap_user_service")->updateByLogin($user->getUsername());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    protected function fetchSids(User $user, Plexcel $plexcel)
    {
        $px = $plexcel->getRessource();

        $attrdefs = array(
            'tokenGroups' => array(
                //    'type' => PLEXCEL_TYPE_BINARY,
                'conv' => PLEXCEL_CONV_BASE64_X_BINARY,
                'conv' => PLEXCEL_CONV_SIDSTR_X_BINARY,
            ),
        );

        \plexcel_set_attrdefs($px, $attrdefs);

        // Obtention du "distinguishedName"
        $params = array(
            'base' => 'DC=ad,DC=arianespace,DC=fr',
            'filter' => '(sAMAccountName='.$user->getUsername().')',
            'scope' => 'sub',
            'attrs' => array('distinguishedName', 'objectSid'),
        );

        $objs = \plexcel_search_objects($px, $params);

        if (!is_array($objs)) {
            throw new \Exception(sprintf('Cannot retrieve user informations for user %s', $user->getUsername()));
        }

        // Obtention du "tokenGroups"
        $params = array(
            'base' => $objs[0]['distinguishedName'],
            'scope' => 'base',
            'attrs' => array('tokenGroups'),
        );

        $user->addGroup($this->bin_to_str_sid($objs[0]['objectSid']));

        $objs = \plexcel_search_objects($px, $params);

        if (is_array($objs) == false || !isset($objs[0]) || !isset($objs[0]['tokenGroups'])) {
            throw new \Exception(sprintf('Cannot retrieve group list for user %s, received : %s', $user->getUsername(), print_r($objs, true)));
        }

        foreach ($objs[0]['tokenGroups'] as $g) {
            $user->addGroup($this->bin_to_str_sid($g));
        }

        return $user;
    }
    // Converts a little-endian hex-number to one, that 'hexdec' can convert
    protected function little_endian($hex)
    {
        $result = '';
        for ($x = strlen($hex) - 2; $x >= 0; $x = $x - 2) {
            $result .= substr($hex, $x, 2);
        }

        return $result;
    }

    // Returns the textual SID
    protected function bin_to_str_sid($binsid)
    {
        $hex_sid = bin2hex($binsid);
        $rev = hexdec(substr($hex_sid, 0, 2));
        $subcount = hexdec(substr($hex_sid, 2, 2));
        $auth = hexdec(substr($hex_sid, 4, 12));
        $result = "$rev-$auth";

        for ($x = 0;$x < $subcount; ++$x) {
            $subauth[$x] = hexdec($this->little_endian(substr($hex_sid, 16 + ($x * 8), 8)));
            $result      .= '-'.$subauth[$x];
        }

        // Cheat by tacking on the S-
        return 'S-'.$result;
    }
}
