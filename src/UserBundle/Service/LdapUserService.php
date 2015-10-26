<?php
/**
 * Created by PhpStorm.
 * User: paul
 * Date: 05/08/15
 * Time: 13:37
 */

namespace UserBundle\Service;


use Doctrine\ORM\EntityManager;
use PhpSpec\Exception\Example\NotEqualException;
use Symfony\Component\DependencyInjection\Container;
use UserBundle\Entity\User;

class LdapUserService
{
    private $em;
    private $container;
    private $dns = ["group_maj","admins","users","managers","division_managers","rhs","directors"];
    private $allMembers = [];


    public function __construct(EntityManager $em, Container $container)
    {
        $this->em = $em;
        $this->container = $container;

        foreach($this->dns as $dn)
        {
            $members = $this->container->get('ldap_service')->getMembers($this->container->getParameter('ldap')[$dn]);
            foreach($members as $memberDn)
            {
                $member = $this->container->get("ldap_service")->getAccountInformation($memberDn);
                if(!in_array($member,$this->allMembers))
                    array_push($this->allMembers, $member);
            }
        }
    }

    public function updateAll()
    {
        $members = $this->getAllUsersInfos();
        foreach($members as $member){
            $this->em->persist($member);
        }
        $this->em->flush();
        foreach($members as $member)
        {
            $this->em->refresh($member);
            $member = $this->setRelations($member);
            $this->em->persist($member);
        }
        $this->em->flush();
        return $members;
    }

    public function updateByLogin($login)
    {
        $ldap = $this->getLdapByLogin($login);
        if($ldap === null)
            throw new \Exception("User not found");
        $user = $this->formatLdapToUser($ldap);
        $this->em->persist($user);
        $this->em->flush();
        $this->em->refresh($user);
        $user = $this->setRelations($user);
        $this->em->persist($user);
        $this->em->flush();
        return $user;

    }

    public function getLdapByLogin($login)
    {
        $allUser = $this->allMembers;
        foreach($allUser as $singleUser)
            if(strtolower($singleUser["samaccountname"]) === strtolower($login))
                return $singleUser;
        return null;
    }

    private function getUserByCn($cn)
    {
        return $this->container->get("ldap_service")->getAccountInformation($cn);
    }

    public function updateByCn($cn)
    {

        return $this->formatLdapToUser($this->getUserByCn($cn));
    }

    public function getAllUsersInfos()
    {
        $return  = $this->allMembers;
        foreach($return as $id => $member) {
            $return[$id] = $this->formatLdapToUser($member);
        }
        return $return;
    }

    private function formatLdapToUser($member)
    {
        $arr = [
            "firstName" =>  "givenname",
            "lastName"  =>  "sn",
            "fullname"  =>  "cn",
            "department"=>  "department",
            "mail"      =>  "mail",
            "division"  =>  "division",

        ];

        $roles = [
            "admins"                => "ROLE_ADMIN",
            "managers"              => "ROLE_MANAGER",
            "division_managers"     => "ROLE_DIVISION_MANAGER",
            "rhs"                   => "ROLE_RH",
            "directors"             => "ROLE_DIRECTOR"
        ];


        //Recherche insensible à la casse
        $login = $member["samaccountname"];

        $user = $this->em->getRepository("UserBundle:User")->findOneByLogin(strtolower($login));
        if($user === null) {
            $user = $this->em->getRepository("UserBundle:User")->findOneByLogin(strtoupper($login));
            if($user === null){
                $user = new User();
            }
        }
        $user->setLogin($login);
        $user->setUsername($login);

        // Valeurs
        foreach($arr as $attr => $val)
            $user = $this->setValToUser($user,$attr,$val,$member);

        // Roles : on les redétermine entièrement pouré viter les duplications
        $user->setRoles(array());
        foreach($roles as $role_name => $role) {
            $user = $this->setRoles($user, $member, $role_name, $role);
        }

        return $user;
    }

    private function setValToUser($user,$attr,$val,$arr)
    {
        $set = "set". ucfirst($attr);
        if(array_key_exists($val,$arr))
            $user->$set($arr[$val]);
        return $user;
    }

    private function setRelations(User $user)
    {
        $personnes = ["manager"];

        foreach($personnes as $personne)
        {
            $ldap = $this->getLdapByLogin($user->getLogin());
            if(array_key_exists($personne,$ldap))
            {
                if(is_array($ldap[$personne]))
                {
                    foreach($ldap[$personne] as $cn)
                    {
                        $pers = $this->getUserByCn($cn);
                        $this->setRelation($user,$pers,$personne);
                    }
                }else{
                    $pers = $this->getUserByCn($ldap[$personne]);
                    $this->setRelation($user,$pers,$personne);
                }

            }
        }

        return $user;
    }

    private function setRelation($user,$relation,$attr)
    {
        $userInstance = $this->em->getRepository("UserBundle:User")->findOneByLogin($relation["samaccountname"]);
        if($userInstance === null)
            $userInstance = $this->updateByLogin($relation["samaccountname"]);
        $set = "set". ucfirst($attr);
        $user->$set($userInstance);
        return $user;
    }

    private function setRoles($user,$ldap,$role_name,$role)
    {

        if(is_array($ldap["memberof"])) {
            if(in_array($this->container->getParameter('ldap')[$role_name], $ldap["memberof"]))
            {
                $user->addRole($role);
            }
        }else{

            if($this->container->getParameter('ldap')[$role_name] === $ldap["memberof"])
            {
                $user->addRole($role);
            }
        }



        //On détermine au minimum le role USER
        if(is_int($user->getRoles()) || !$user->getRoles()) {
            $user->addRole("ROLE_USER");
        }

        $roles = $user->getRoles();

        if(!in_array('ROLE_ALLOWED_TO_SWITCH', $roles) && in_array('ROLE_ADMIN', $roles)) {
            $user->addRole('ROLE_ALLOWED_TO_SWITCH');
        }

        return $user;
    }

}