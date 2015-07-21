<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 16/06/2015
 * Time: 11:03
 */
namespace UserBundle\Entity;

use Arianespace\PlexcelBundle\Security\User\UserInterface;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\Role;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="UserBundle\Entity\Repository\UserRepository")
 * //implements UserInterface de plexcel pour Plexcel
 */
class User implements UserInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    protected $roles;
    protected $username;
    protected $password;
    protected $salt;
    private $sids;
    private $email;
    protected $fullName;



    /**
     * @ORM\OneToMany(targetEntity="FormGeneratorBundle\Entity\ValuationMeet", mappedBy="assessor", cascade={"persist"})
     */
    private $assessorValuationMeets;

    /**
     * @ORM\OneToMany(targetEntity="FormGeneratorBundle\Entity\ValuationMeet", mappedBy="assessed", cascade={"persist"})
     */
    private $assessedValuationMeets;

    /**
     * @ORM\OneToMany(targetEntity="FormGeneratorBundle\Entity\ProfessionalMeet", mappedBy="assessor", cascade={"persist"})
     */
    private $assessorProfessionalMeets;

    /**
     * @ORM\OneToMany(targetEntity="FormGeneratorBundle\Entity\ProfessionalMeet", mappedBy="assessed", cascade={"persist"})
     */
    private $assessedProfessionalMeets;
    /**
     * @ORM\OneToMany(targetEntity="FormGeneratorBundle\Entity\ConditionsMeet", mappedBy="assessor", cascade={"persist"})
     */
    private $assessorConditionsMeets;

    /**
     * @ORM\OneToMany(targetEntity="FormGeneratorBundle\Entity\ConditionsMeet", mappedBy="assessed", cascade={"persist"})
     */
    private $assessedConditionsMeets;




    public function __construct()
    {
     //   parent::__construct();
        $this->assessorValuationMeets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->assessedValuationMeets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->assessorProfessionalMeets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->assessedProfessionalMeets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->assessorConditionsMeets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->assessedConditionseetss = new \Doctrine\Common\Collections\ArrayCollection();
        $this->roles = array('ROLE_USER', 'ROLE_ADMIN');
        $this->sids  = array();
    }

    public function __toString()
    {
        return (string)$this->getUsername();
    }

    public function getDisplayName(){

        return $this->getUsername();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Add assessorValuationMeets
     *
     * @param \FormGeneratorBundle\Entity\ValuationMeet $assessorValuationMeets
     * @return User
     */
    public function addAssessorValuationMeet(\FormGeneratorBundle\Entity\ValuationMeet $assessorValuationMeets)
    {
        $this->assessorValuationMeets[] = $assessorValuationMeets;

        return $this;
    }

    /**
     * Remove assessorValuationMeets
     *
     * @param \FormGeneratorBundle\Entity\ValuationMeet $assessorValuationMeets
     */
    public function removeAssessorValuationMeet(\FormGeneratorBundle\Entity\ValuationMeet $assessorValuationMeets)
    {
        $this->assessorValuationMeets->removeElement($assessorValuationMeets);
    }

    /**
     * Get assessorValuationMeets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAssessorValuationMeets()
    {
        return $this->assessorValuationMeets;
    }

    /**
     * Add assessedValuationMeets
     *
     * @param \FormGeneratorBundle\Entity\ValuationMeet $assessedValuationMeets
     * @return User
     */
    public function addAssessedValuationMeet(\FormGeneratorBundle\Entity\ValuationMeet $assessedValuationMeets)
    {
        $this->assessedValuationMeets[] = $assessedValuationMeets;

        return $this;
    }

    /**
     * Remove assessedValuationMeets
     *
     * @param \FormGeneratorBundle\Entity\ValuationMeet $assessedValuationMeets
     */
    public function removeAssessedValuationMeet(\FormGeneratorBundle\Entity\ValuationMeet $assessedValuationMeets)
    {
        $this->assessedValuationMeets->removeElement($assessedValuationMeets);
    }

    /**
     * Get assessedValuationMeets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAssessedValuationMeets()
    {
        return $this->assessedValuationMeets;
    }

    /**
     * Add assessorProfessionalMeets
     *
     * @param \FormGeneratorBundle\Entity\ProfessionalMeet $assessorProfessionalMeets
     * @return User
     */
    public function addAssessorProfessionalMeet(\FormGeneratorBundle\Entity\ProfessionalMeet $assessorProfessionalMeets)
    {
        $this->assessorProfessionalMeets[] = $assessorProfessionalMeets;

        return $this;
    }

    /**
     * Remove assessorProfessionalMeets
     *
     * @param \FormGeneratorBundle\Entity\ProfessionalMeet $assessorProfessionalMeets
     */
    public function removeAssessorProfessionalMeet(\FormGeneratorBundle\Entity\ProfessionalMeet $assessorProfessionalMeets)
    {
        $this->assessorProfessionalMeets->removeElement($assessorProfessionalMeets);
    }

    /**
     * Get assessorProfessionalMeets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAssessorProfessionalMeets()
    {
        return $this->assessorProfessionalMeets;
    }

    /**
     * Add assessedProfessionalMeets
     *
     * @param \FormGeneratorBundle\Entity\ProfessionalMeet $assessedProfessionalMeets
     * @return User
     */
    public function addAssessedProfessionalMeet(\FormGeneratorBundle\Entity\ProfessionalMeet $assessedProfessionalMeets)
    {
        $this->assessedProfessionalMeets[] = $assessedProfessionalMeets;

        return $this;
    }

    /**
     * Remove assessedProfessionalMeets
     *
     * @param \FormGeneratorBundle\Entity\ProfessionalMeet $assessedProfessionalMeets
     */
    public function removeAssessedProfessionalMeet(\FormGeneratorBundle\Entity\ProfessionalMeet $assessedProfessionalMeets)
    {
        $this->assessedProfessionalMeets->removeElement($assessedProfessionalMeets);
    }

    /**
     * Get assessedProfessionalMeets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAssessedProfessionalMeets()
    {
        return $this->assessedProfessionalMeets;
    }
    /**
     * Add assessorConditionsMeets
     *
     * @param \FormGeneratorBundle\Entity\ConditionsMeet $assessorConditionsMeets
     * @return User
     */
    public function addAssessorConditionsMeet(\FormGeneratorBundle\Entity\ConditionsMeet $assessorConditionsMeets)
    {
        $this->assessorConditionsMeets[] = $assessorConditionsMeets;

        return $this;
    }

    /**
     * Remove assessorConditionsMeets
     *
     * @param \FormGeneratorBundle\Entity\ConditionsMeet $assessorConditionsMeets
     */
    public function removeAssessorConditionsMeet(\FormGeneratorBundle\Entity\ConditionsMeet $assessorConditionsMeets)
    {
        $this->assessorConditionsMeets->removeElement($assessorConditionsMeets);
    }

    /**
     * Get assessorConditionsMeets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAssessorConditionsMeets()
    {
        return $this->assessorConditionsMeets;
    }

    /**
     * Add assessedConditionsMeets
     *
     * @param \FormGeneratorBundle\Entity\ConditionsMeet $assessedConditionsMeets
     * @return User
     */
    public function addAssessedConditionsMeet(\FormGeneratorBundle\Entity\ConditionsMeet $assessedConditionsMeets)
    {
        $this->assessedConditionsMeets[] = $assessedConditionsMeets;

        return $this;
    }

    /**
     * Remove assessedConditionsMeets
     *
     * @param \FormGeneratorBundle\Entity\ConditionsMeet $assessedConditionsMeets
     */
    public function removeAssessedConditionsMeet(\FormGeneratorBundle\Entity\ConditionsMeet $assessedConditionsMeets)
    {
        $this->assessedConditionsMeets->removeElement($assessedConditionsMeets);
    }

    /**
     * Get assessedConditionsMeets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAssessedConditionsMeets()
    {
        return $this->assessedConditionsMeets;
    }


    public function getGroups()
    {
        return $this->sids;
    }

    public function addGroup($sid)
    {
        $this->sids[] = $sid;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        // TODO: Implement getRoles() method.
        return $this->roles;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        // TODO: Implement getPassword() method.
        return $this->password;
    }

    public function setPassword($password)
    {
        //Do no set password (don't need it)
        $this->password = null;

        return $this;
    }


    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
        return $this->salt;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        // TODO: Implement getUsername() method.
        return $this->username;

    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }
    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $fullName
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->fullName;
    }


}
