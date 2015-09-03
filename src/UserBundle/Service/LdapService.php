<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 05/08/2015
 * Time: 09:27
 */

namespace UserBundle\Service;


use PhpSpec\ServiceContainer;
use Symfony\Component\DependencyInjection\Container;

class LdapService {


    private $container;
    public $mode;
    public $host;
    public $connexion;
    public $bind;
    public $entry;
    public $sr;
    private $ldap_config;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->ldap_config = $this->container->getParameter('ldap');
    }


    /**
     * constructeur par défaut : utilise ae-e-dc1
     *
     * @return ldap
     */

    public function ldap($dc = null)
    {
        if ($dc != null)
        {
            $dcs = explode("|", $this->ldap_config['dclist']);
            if (!in_array($dc, $dcs))
                $ldaphost = $this->ldap_config['dc'];//sfConfig::get('app_ldap_dc');  // dc par defaut
            else
                $ldaphost = $dc;  // votre serveur LDAP

        }
        else {
            $ldaphost = $this->ldap_config['dc'];
        }

        $this->host = $ldaphost;
        $ldapport = $this->ldap_config['port'];  // votre port de serveur LDAP

        // Connexion LDAP
        $this->connexion = ldap_connect($ldaphost,   $ldapport);
        if (!$this->connexion)
            throw new Exception("Impossible de se connecter au serveur LDAP");

        $this->bind = ldap_bind($this->connexion,
           $this->ldap_config['login'],
            $this->ldap_config['password']);
        if (!$this->bind)
            throw new Exception("Echec du bind au serveur LDAP");

    }

    public function close()
    {
        ldap_close($this->connexion);

    }

    public function getAllGroups()
    {

        $this->ldap();
        $filter="(&(objectClass=top)(objectClass=group))";
        $justthese = array();
        $this->sr = ldap_search($this->connexion, $this->ldap_config['dn'], $filter,  $justthese);
        $info = ldap_get_entries($this->connexion, $this->sr);
        //$this->entry = ldap_first_entry($this->connexion, $this->sr);
        //return $info;
        if ($info["count"] == $this->ldap_config['limit'])
            error_log("STYX - Attention, la limite de résultats (" . $this->ldap_config['limit'] . ") a été retournée par le DC");

        $this->close();

        return $info;
    }

    /**
     * Retourne tous les memberOf du samaccountname $login
     *
     * @param string $login
     * @return array
     */
    public function getMemberOf($login)
    {
        $this->ldap();
        $filter="(sAMAccountName=" . $login . ")";
        $justthese = array("memberOf");
        $sr=ldap_search($this->connexion, $this->ldap_config['dn'], $filter, $justthese);
        $info = ldap_get_entries($this->connexion, $sr);
        $this->close();

        if (!isset($info[0]["memberof"]))
            return array();
        else
            return $info[0]["memberof"];
    }

    /**
     * retourne un tableau indexé des membres
     *
     * @param unknown_type $dn
     */
    public function getMembers($dn)
    {

        $this->ldap();

        $filter    = "(distinguishedName=". self::escapeSpecialChars($dn) . ")";
        $justthese = array("member");
        $this->sr  = ldap_search($this->connexion, $this->ldap_config['dn'], $filter,  $justthese);
        $info      = ldap_get_entries($this->connexion, $this->sr);

        $tab = array();
        for ($i = 0; isset($info[0]["member"][$i]); $i++)
        {
            $tmp = iconv("ISO-8859-1", "UTF-8", $info[0]['member'][$i]);

            if ($this->isGroup($tmp))
            {
                $tab_tmp = $this->getMembers($tmp);

                $tab = array_merge($tab, $tab_tmp);
            }
            else
                $tab[] = iconv("ISO-8859-1", "UTF-8", $info[0]['member'][$i]);
        }

        return (array_unique($tab));
    }

    public function isGroup($dn)
    {
        $filter    = "(distinguishedName=". self::escapeSpecialChars($dn) . ")";
        $justthese = array("objectClass");
        $this->sr  = ldap_search($this->connexion, $this->ldap_config['dn'], $filter,  $justthese);
        $info      = ldap_get_entries($this->connexion, $this->sr);

        for ($i = 0; isset($info[0]["objectclass"][$i]); $i++)
            if (iconv("ISO-8859-1", "UTF-8", $info[0]["objectclass"][$i]) == "group")
                return (true);

        return (false);
    }

    /**
     *   virgule ()
     *   Signe plus (+)
     *   guillemets doubles (" ")
     *   Barre oblique inverse (\)
     *   Signes « inférieur à » et « supérieur à » (< ou >)
     *   Point-virgule (;)
     */

    /**
     * recupere les informations d un utilisateur en fonction de son DN et retourne un tableau associatif cle->valeur
     *
     * @param unknown_type $dn
     * @param unknown_type $justthese
     * @return unknown
     */
    public function getAccountInformation($dn, $justthese = array())
    {
        $this->ldap();
        $filter    = "(distinguishedName=". self::escapeSpecialChars($dn) . ")";
        $this->sr  = ldap_search($this->connexion, $this->ldap_config['dn'], $filter,  $justthese);
        $info      = ldap_get_entries($this->connexion, $this->sr);

        if ($info["count"]== 0)
            return array();

        $tabretour = array();
        $info = $info[0];

        for ($i = 0; isset($info[$i]); $i++)
        {
            $cle = $info[$i];
            if ($info[$cle]["count"] > 1)
            {
                for ($j = 0; isset($info[$cle][$j]); $j++)
                {
                    $tabretour[$cle][] = iconv("ISO-8859-1", "UTF-8", $info[$cle][$j]);
                }
            }
            else
            {
                $tabretour[$cle] = iconv("ISO-8859-1", "UTF-8", $info[$cle][0]);
            }

        }

        $this->close();
        //on regarde si tous les justthese y sont. on complete $tabretour avec des chaines vides ou des tableaux vides pour les champs multivalues
  //      $tabmultivalue = explode("|", sfConfig::get('app_tabchamps_multivalue'));
//        for ($i = 0; isset($justthese[$i]); $i++)
//        {
//            if (!isset($tabretour[$justthese[$i]]))
//            {
//                $tabretour[$justthese[$i]] = "";
//            }
//
//        }

        return $tabretour;
    }

    /**
     * permet d echapper les caracteres speciaux dans les filter
     * consulter : http://www.table-ascii.com/
     * @param unknown_type $filter
     */

    public static function escapeSpecialChars($str)
    {
        return str_replace(
            array( '\\',   '*',    '(',    ')',    '+',    'é',    'è',     'ô',    'ê',    'â',    'ï',    "î",    "ç",    "Î",    "Ë",    "È",    "É",    "Ê",    "ë",    "µ",    "Ï"    ),
            array( '\\5c', '\\2a', '\\28', '\\29', '\\2B', "\\E9", "\\E8" , "\\F4", "\\E4", "\\E2", "\\EF", "\\EE", "\\E7", "\\CE", "\\CB", "\\C8", "\\C9", "\\CA", "\\EB", "\\B5", "\\CF"  ),
            $str
        );
    }

}