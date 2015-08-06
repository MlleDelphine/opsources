<?php

/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 16/06/2015
 * Time: 11:03.
 */

namespace UserBundle\Entity;

use Arianespace\PlexcelBundle\Security\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\Role;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * User.
 *
 * @ORM\Table(name="opus_users")
 * @ORM\Entity(repositoryClass="UserBundle\Entity\Repository\UserRepository")
 * //implements UserInterface de plexcel pour Plexcel
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @var int
     *
     * @ORM\Column(name="roles", type="array", nullable=true)
     */
    protected $roles;
    /**
     * @var int
     *
     * @ORM\Column(name="sids", type="array", nullable=true)
     */
    private $sids;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=32, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=32, nullable=true)
     */
    private $salt;

    /**
     * @var int
     *
     * @ORM\Column(name="fullname", type="string", nullable=true)
     */
    protected $fullName;
    /**
     * @var int
     *
     * @ORM\Column(name="func_manager_id", type="bigint", nullable=true)
     */
    private $funcManagerId;

    /**
     * @var string
     *
     * @ORM\Column(name="guid", type="string", length=64, nullable=true)
     */
    private $guid;

    /**
     * @var int
     *
     * @ORM\Column(name="responsable", type="smallint", nullable=true)
     */
    private $responsable = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=255, nullable=false)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=128, nullable=true)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="division", type="string", length=255, nullable=true)
     */
    private $division;

    /**
     * @var string
     *
     * @ORM\Column(name="department", type="string", length=255, nullable=true)
     */
    private $department;

    /**
     * @var string
     *
     * @ORM\Column(name="classification", type="string", length=255, nullable=true)
     */
    private $classification;

    /**
     * @var string
     *
     * @ORM\Column(name="fonction", type="text", nullable=true)
     */
    private $fonction;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="entry_date", type="datetime", nullable=true)
     */
    private $entryDate;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="bigint", nullable=false)
     */
    private $status;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \OpusUsers
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="manager_id", referencedColumnName="id")
     * })
     */
    private $manager;

    /**
     * @var \OpusJob
     *
     * @ORM\ManyToOne(targetEntity="GeneratorBundle\Entity\OpusJob")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="job_id", referencedColumnName="id")
     * })
     */
    private $job;

    /**
     * @var \OpusJob
     *
     * @ORM\ManyToOne(targetEntity="GeneratorBundle\Entity\OpusJob")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="job2_id", referencedColumnName="id")
     * })
     */
    private $job2;


    /**
     * @ORM\OneToMany(targetEntity="GeneratorBundle\Entity\OpusSheet", mappedBy="evaluator", cascade={"persist"})
     */
    private $opusSheetsEvaluator;

    /**
     * @ORM\OneToMany(targetEntity="GeneratorBundle\Entity\OpusSheet", mappedBy="evaluate", cascade={"persist"})
     */
    private $opusSheetsEvaluate;

    /**
     * @ORM\OneToMany(targetEntity="GeneratorBundle\Entity\OpusSheet", mappedBy="superior", cascade={"persist"})
     */
    private $opusSheetsSuperior;

    /**
     * @ORM\OneToMany(targetEntity="GeneratorBundle\Entity\OpusSheet", mappedBy="director", cascade={"persist"})
     */
    private $opusSheetsDirector;

    /**
     * @ORM\OneToMany(targetEntity="GeneratorBundle\Entity\OpusSheet", mappedBy="responsable", cascade={"persist"})
     */
    private $opusSheetsResponsable;

    public function __construct()
    {
        //   parent::__construct();
        $this->assessorValuationMeets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->assessedValuationMeets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->assessorProfessionalMeets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->assessedProfessionalMeets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->assessorConditionsMeets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->assessedConditionseetss = new \Doctrine\Common\Collections\ArrayCollection();
        $roles = array('ROLE_USER');
        $this->roles = $roles;
        $this->sids = array();
        $this->status = 0;

        $this->opusSheetsEvaluator = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->getFirstName().' '.$this->getLastName();
    }

    public function getDisplayName()
    {
        return $this->getUsername();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
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

    /**
     * Set funcManagerId.
     *
     * @param int $funcManagerId
     *
     * @return User
     */
    public function setFuncManagerId($funcManagerId)
    {
        $this->funcManagerId = $funcManagerId;

        return $this;
    }

    /**
     * Get funcManagerId.
     *
     * @return int
     */
    public function getFuncManagerId()
    {
        return $this->funcManagerId;
    }

    /**
     * Set guid.
     *
     * @param string $guid
     *
     * @return User
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * Get guid.
     *
     * @return string
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * Set responsable.
     *
     * @param int $responsable
     *
     * @return User
     */
    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable.
     *
     * @return int
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * Set lastName.
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set firstName.
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set login.
     *
     * @param string $login
     *
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login.
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set mail.
     *
     * @param string $mail
     *
     * @return User
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail.
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set division.
     *
     * @param string $division
     *
     * @return User
     */
    public function setDivision($division)
    {
        $this->division = $division;

        return $this;
    }

    /**
     * Get division.
     *
     * @return string
     */
    public function getDivision()
    {
        return $this->division;
    }

    /**
     * Set department.
     *
     * @param string $department
     *
     * @return User
     */
    public function setDepartment($department)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department.
     *
     * @return string
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set classification.
     *
     * @param string $classification
     *
     * @return User
     */
    public function setClassification($classification)
    {
        $this->classification = $classification;

        return $this;
    }

    /**
     * Get classification.
     *
     * @return string
     */
    public function getClassification()
    {
        return $this->classification;
    }

    /**
     * Set fonction.
     *
     * @param string $fonction
     *
     * @return User
     */
    public function setFonction($fonction)
    {
        $this->fonction = $fonction;

        return $this;
    }

    /**
     * Get fonction.
     *
     * @return string
     */
    public function getFonction()
    {
        return $this->fonction;
    }

    /**
     * Set entryDate.
     *
     * @param \DateTime $entryDate
     *
     * @return User
     */
    public function setEntryDate($entryDate)
    {
        $this->entryDate = $entryDate;

        return $this;
    }

    /**
     * Get entryDate.
     *
     * @return \DateTime
     */
    public function getEntryDate()
    {
        return $this->entryDate;
    }

    /**
     * Set status.
     *
     * @param int $status
     *
     * @return User
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set manager.
     *
     * @param \UserBundle\Entity\User $manager
     *
     * @return User
     */
    public function setManager(\UserBundle\Entity\User $manager = null)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Get manager.
     *
     * @return \UserBundle\Entity\User
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Set job.
     *
     * @param \GeneratorBundle\Entity\OpusJob $job
     *
     * @return User
     */
    public function setJob(\GeneratorBundle\Entity\OpusJob $job = null)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * Get job.
     *
     * @return \GeneratorBundle\Entity\OpusJob
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Set job2.
     *
     * @param \GeneratorBundle\Entity\OpusJob $job2
     *
     * @return User
     */
    public function setJob2(\GeneratorBundle\Entity\OpusJob $job2 = null)
    {
        $this->job2 = $job2;

        return $this;
    }

    /**
     * Get job2.
     *
     * @return \GeneratorBundle\Entity\OpusJob
     */
    public function getJob2()
    {
        return $this->job2;
    }

    /**
     * Add opusSheetsEvaluator.
     *
     * @param \GeneratorBundle\Entity\OpusSheet $opusSheetsEvaluator
     *
     * @return User
     */
    public function addOpusSheetsEvaluator(\GeneratorBundle\Entity\OpusSheet $opusSheetsEvaluator)
    {
        $this->opusSheetsEvaluator[] = $opusSheetsEvaluator;

        return $this;
    }

    /**
     * Remove opusSheetsEvaluator.
     *
     * @param \GeneratorBundle\Entity\OpusSheet $opusSheetsEvaluator
     */
    public function removeOpusSheetsEvaluator(\GeneratorBundle\Entity\OpusSheet $opusSheetsEvaluator)
    {
        $this->opusSheetsEvaluator->removeElement($opusSheetsEvaluator);
    }

    /**
     * Get opusSheetsEvaluator.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOpusSheetsEvaluator()
    {
        return $this->opusSheetsEvaluator;
    }

    /**
     * Add opusSheetsEvaluate.
     *
     * @param \GeneratorBundle\Entity\OpusSheet $opusSheetsEvaluate
     *
     * @return User
     */
    public function addOpusSheetsEvaluate(\GeneratorBundle\Entity\OpusSheet $opusSheetsEvaluate)
    {
        $this->opusSheetsEvaluate[] = $opusSheetsEvaluate;

        return $this;
    }

    /**
     * Remove opusSheetsEvaluate.
     *
     * @param \GeneratorBundle\Entity\OpusSheet $opusSheetsEvaluate
     */
    public function removeOpusSheetsEvaluate(\GeneratorBundle\Entity\OpusSheet $opusSheetsEvaluate)
    {
        $this->opusSheetsEvaluate->removeElement($opusSheetsEvaluate);
    }

    /**
     * Get opusSheetsEvaluate.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOpusSheetsEvaluate()
    {
        return $this->opusSheetsEvaluate;
    }

    /**
     * Add opusSheetsSuperior.
     *
     * @param \GeneratorBundle\Entity\OpusSheet $opusSheetsSuperior
     *
     * @return User
     */
    public function addOpusSheetsSuperior(\GeneratorBundle\Entity\OpusSheet $opusSheetsSuperior)
    {
        $this->opusSheetsSuperior[] = $opusSheetsSuperior;

        return $this;
    }

    /**
     * Remove opusSheetsSuperior.
     *
     * @param \GeneratorBundle\Entity\OpusSheet $opusSheetsSuperior
     */
    public function removeOpusSheetsSuperior(\GeneratorBundle\Entity\OpusSheet $opusSheetsSuperior)
    {
        $this->opusSheetsSuperior->removeElement($opusSheetsSuperior);
    }

    /**
     * Get opusSheetsSuperior.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOpusSheetsSuperior()
    {
        return $this->opusSheetsSuperior;
    }

    /**
     * Add opusSheetsDirector.
     *
     * @param \GeneratorBundle\Entity\OpusSheet $opusSheetsDirector
     *
     * @return User
     */
    public function addOpusSheetsDirector(\GeneratorBundle\Entity\OpusSheet $opusSheetsDirector)
    {
        $this->opusSheetsDirector[] = $opusSheetsDirector;

        return $this;
    }

    /**
     * Remove opusSheetsDirector.
     *
     * @param \GeneratorBundle\Entity\OpusSheet $opusSheetsDirector
     */
    public function removeOpusSheetsDirector(\GeneratorBundle\Entity\OpusSheet $opusSheetsDirector)
    {
        $this->opusSheetsDirector->removeElement($opusSheetsDirector);
    }

    /**
     * Get opusSheetsDirector.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOpusSheetsDirector()
    {
        return $this->opusSheetsDirector;
    }

    /**
     * Add opusSheetsResponsable.
     *
     * @param \GeneratorBundle\Entity\OpusSheet $opusSheetsResponsable
     *
     * @return User
     */
    public function addOpusSheetsResponsable(\GeneratorBundle\Entity\OpusSheet $opusSheetsResponsable)
    {
        $this->opusSheetsResponsable[] = $opusSheetsResponsable;

        return $this;
    }

    /**
     * Remove opusSheetsResponsable.
     *
     * @param \GeneratorBundle\Entity\OpusSheet $opusSheetsResponsable
     */
    public function removeOpusSheetsResponsable(\GeneratorBundle\Entity\OpusSheet $opusSheetsResponsable)
    {
        $this->opusSheetsResponsable->removeElement($opusSheetsResponsable);
    }

    /**
     * Get opusSheetsResponsable.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOpusSheetsResponsable()
    {
        return $this->opusSheetsResponsable;
    }

    /**
     * Set roles.
     *
     * @param string $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Set pasword.
     *
     * @param string $pasword
     *
     * @return User
     */
    public function setPasword($pasword)
    {
        $this->pasword = $pasword;

        return $this;
    }

    /**
     * Get pasword.
     *
     * @return string
     */
    public function getPasword()
    {
        return $this->pasword;
    }

    /**
     * Set salt.
     *
     * @param string $salt
     *
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }
}
