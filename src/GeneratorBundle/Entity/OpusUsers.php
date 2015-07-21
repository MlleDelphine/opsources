<?php

namespace GeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OpusUsers
 *
 * @ORM\Table(name="opus_users", indexes={@ORM\Index(name="opus_users_func_manager_id", columns={"func_manager_id"}), @ORM\Index(name="opus_users_job2_id", columns={"job2_id"}), @ORM\Index(name="opus_users_job_id", columns={"job_id"}), @ORM\Index(name="opus_users_manager_id", columns={"manager_id"})})
 * @ORM\Entity
 */
class OpusUsers
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="opus_users_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var integer
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
     * @var integer
     *
     * @ORM\Column(name="responsable", type="smallint", nullable=true)
     */
    private $responsable = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=128, nullable=false)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=128, nullable=false)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=32, nullable=false)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=128, nullable=false)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="division", type="string", length=32, nullable=true)
     */
    private $division;

    /**
     * @var string
     *
     * @ORM\Column(name="department", type="string", length=32, nullable=true)
     */
    private $department;

    /**
     * @var string
     *
     * @ORM\Column(name="classification", type="string", length=32, nullable=true)
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
     * @var integer
     *
     * @ORM\Column(name="status", type="bigint", nullable=false)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \OpusUsers
     *
     * @ORM\ManyToOne(targetEntity="OpusUsers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="manager_id", referencedColumnName="id")
     * })
     */
    private $manager;

    /**
     * @var \OpusJob
     *
     * @ORM\ManyToOne(targetEntity="OpusJob")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="job_id", referencedColumnName="id")
     * })
     */
    private $job;

    /**
     * @var \OpusJob
     *
     * @ORM\ManyToOne(targetEntity="OpusJob")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="job2_id", referencedColumnName="id")
     * })
     */
    private $job2;



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
     * Set funcManagerId
     *
     * @param integer $funcManagerId
     * @return OpusUsers
     */
    public function setFuncManagerId($funcManagerId)
    {
        $this->funcManagerId = $funcManagerId;

        return $this;
    }

    /**
     * Get funcManagerId
     *
     * @return integer 
     */
    public function getFuncManagerId()
    {
        return $this->funcManagerId;
    }

    /**
     * Set guid
     *
     * @param string $guid
     * @return OpusUsers
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * Get guid
     *
     * @return string 
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * Set responsable
     *
     * @param integer $responsable
     * @return OpusUsers
     */
    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable
     *
     * @return integer 
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return OpusUsers
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return OpusUsers
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set login
     *
     * @param string $login
     * @return OpusUsers
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return OpusUsers
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string 
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set division
     *
     * @param string $division
     * @return OpusUsers
     */
    public function setDivision($division)
    {
        $this->division = $division;

        return $this;
    }

    /**
     * Get division
     *
     * @return string 
     */
    public function getDivision()
    {
        return $this->division;
    }

    /**
     * Set department
     *
     * @param string $department
     * @return OpusUsers
     */
    public function setDepartment($department)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department
     *
     * @return string 
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set classification
     *
     * @param string $classification
     * @return OpusUsers
     */
    public function setClassification($classification)
    {
        $this->classification = $classification;

        return $this;
    }

    /**
     * Get classification
     *
     * @return string 
     */
    public function getClassification()
    {
        return $this->classification;
    }

    /**
     * Set fonction
     *
     * @param string $fonction
     * @return OpusUsers
     */
    public function setFonction($fonction)
    {
        $this->fonction = $fonction;

        return $this;
    }

    /**
     * Get fonction
     *
     * @return string 
     */
    public function getFonction()
    {
        return $this->fonction;
    }

    /**
     * Set entryDate
     *
     * @param \DateTime $entryDate
     * @return OpusUsers
     */
    public function setEntryDate($entryDate)
    {
        $this->entryDate = $entryDate;

        return $this;
    }

    /**
     * Get entryDate
     *
     * @return \DateTime 
     */
    public function getEntryDate()
    {
        return $this->entryDate;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return OpusUsers
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return OpusUsers
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return OpusUsers
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set manager
     *
     * @param \OldOpusBundle\Entity\OpusUsers $manager
     * @return OpusUsers
     */
    public function setManager(\OldOpusBundle\Entity\OpusUsers $manager = null)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Get manager
     *
     * @return \OldOpusBundle\Entity\OpusUsers 
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Set job
     *
     * @param \OldOpusBundle\Entity\OpusJob $job
     * @return OpusUsers
     */
    public function setJob(\OldOpusBundle\Entity\OpusJob $job = null)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * Get job
     *
     * @return \OldOpusBundle\Entity\OpusJob 
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Set job2
     *
     * @param \OldOpusBundle\Entity\OpusJob $job2
     * @return OpusUsers
     */
    public function setJob2(\OldOpusBundle\Entity\OpusJob $job2 = null)
    {
        $this->job2 = $job2;

        return $this;
    }

    /**
     * Get job2
     *
     * @return \OldOpusBundle\Entity\OpusJob 
     */
    public function getJob2()
    {
        return $this->job2;
    }
}
