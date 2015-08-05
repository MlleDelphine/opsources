<?php
/**
 * Created by PhpStorm.
 * User: paul
 * Date: 05/08/15
 * Time: 13:37
 */

namespace UserBundle\Service;


use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use UserBundle\Entity\User;

class LdapUserService
{
    private $em;
    private $container;
    private $dns = ["group_maj","admins","users","managers","division_managers","rhs","directors"];

    public function __construct(EntityManager $em, Container $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function updateAll()
    {
        $members = $this->getAllUsersInfos();
/*        dump($members);die;*/
        foreach($members as $member)
            $this->em->persist($member);
        $this->em->flush();

    }

    public function updateByLogin($login)
    {

    }

    public function updateByCn($cn)
    {
        $ldap = $this->container->get("ldap_service")->getAccountInformation($cn);
        return $this->formatLdapToUser($ldap);
    }

    public function getAllUsersInfos()
    {
        $return  = [];
        foreach($this->dns as $dn)
        {
            $membres = $this->container->get('ldap_service')->getMembers($this->container->getParameter('ldap')[$dn]);
            foreach($membres as $membreDn)
            {
                $membre = $this->container->get("ldap_service")->getAccountInformation($membreDn);
                if(!in_array($membre,$return))
                    array_push($return, $membre);
            }
        }
        foreach($return as $id => $membre) {
            $return[$id] = $this->formatLdapToUser($membre);
        }
        return $return;
    }

    private function formatLdapToUser($membre)
    {
        $arr = [
            "firstName" =>  "givenname",
            "lastName"  =>  "sn",
            "fullname"  =>  "cn",
            "username"  =>  "name",
            "department"=>  "department",
            "mail"      =>  "mail",
            "division"  =>  "division",

        ];

        $personnes = ["manager"];

        $roles = [
            "admins"                => "ROLE_ADMIN",
            "managers"              => "ROLE_MANAGER",
            "division_managers"     => "ROLE_DIVISION_MANAGER",
            "rhs"                   => "ROLE_RH",
            "directors"             => "ROLE_DIRECTOR"
        ];


        $login = $membre["samaccountname"];
        $user = $this->em->getRepository("UserBundle:User")->findOneByLogin($login);
        if($user === null)
            $user = new User();


        $user->setLogin($login);


        //  Relation
        /*foreach($personnes as $person)
            if(array_key_exists($person,$membre))
                $user = $this->setRelation($user,$person,$this->updateByCn($membre[$person]));*/

        // Valeurs
        foreach($arr as $attr => $val)
            $user = $this->setValToUser($user,$attr,$val,$membre);

        // Roles
        foreach($roles as $role_name => $role)
            $user = $this->setRoles($user,$membre,$role_name,$role);

        return $user;
    }
    private function setValToUser($user,$attr,$val,$arr)
    {
        $set = "set". ucfirst($attr);
        if(array_key_exists($val,$arr))
            $user->$set($arr[$val]);
        return $user;
    }

    private function setRelation($user,$attr,$val)
    {
        $set = "set". ucfirst($attr);
        $user->$set($val);
        return $user;
    }

    private function setRoles($user,$ldap, $role_name, $role)
    {
        if(is_array($ldap["memberof"]))
        {
            if(in_array($this->container->getParameter('ldap')[$role_name],$ldap["memberof"]))
            {
                $roles = $user->getRoles();
                if(is_array($roles))
                    $user->setRoles(array_push($roles,$role));
                else
                    $user->setRoles([$role]);
            }
        }else{
            if($this->container->getParameter('ldap')[$role_name] === $ldap["memberof"])
            {
                $roles = $user->getRoles();
                if(is_array($roles))
                    $user->setRoles(array_push($roles,$role));
                else
                    $user->setRoles([$role]);
            }
        }

        return $user;
    }


}