<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 25/08/2015
 * Time: 16:44
 */

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;


class CheckRoleService {

    private $roleHierarchy;

    /**
     * Constructor
     *
     * @param RoleHierarchyInterface $roleHierarchy
     */
    public function __construct(RoleHierarchyInterface $roleHierarchy, EntityManager $entityManager)
    {
        $this->roleHierarchy = $roleHierarchy;
        $this->entityManager = $entityManager;
    }

    /**
     * isGranted
     *
     * @param string $role
     * @param $user
     * @return bool
     */
    public function isGranted($role, $user) {

        $role = new Role($role);

            foreach ($user->getRoles() as $userRole) {
                if (in_array($role, $this->roleHierarchy->getReachableRoles(array(new Role($userRole))))) {
                    return true;
                }
            }


        return false;
    }

}