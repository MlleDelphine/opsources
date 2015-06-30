<?php

namespace FormGeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ValuationMeet
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FormGeneratorBundle\Entity\Repository\ValuationMeetRepository")
 */
class ValuationMeet
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var \DateTime
     * @ORM\Column(name="meetDate", type="datetime")
     */

    private $meetDate;

    /**
     * @ORM\ManyToOne(targetEntity="FormGeneratorBundle\Entity\Status", inversedBy="valuationMeets", cascade={"persist"})
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="assessorValuationMeets", cascade={"persist"})
     */
    private $assessor;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="assessedValuationMeets", cascade={"persist"})
     */
    private $assessed;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;

    /**
     * @ORM\OneToMany(targetEntity="FormGeneratorBundle\Entity\ValuationAttribute", mappedBy="valuationMeet", cascade={"remove", "persist"})
     */
    private $attributes;

    /**
     * @ORM\OneToMany(targetEntity="FormGeneratorBundle\Entity\Skill", mappedBy="valuationMeet", cascade={"remove", "persist"})
     */
    private $skills;


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
     * Set name
     *
     * @param string $name
     * @return ValuationMeet
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return ValuationMeet
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return ValuationMeet
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attributes = new \Doctrine\Common\Collections\ArrayCollection();
        // $this->created = new \Datetime();

    }

    /**
     * Add attributes
     *
     * @param \FormGeneratorBundle\Entity\ValuationAttribute $attributes
     * @return ValuationMeet
     */
    public function addAttribute(\FormGeneratorBundle\Entity\ValuationAttribute $attributes)
    {
        $attributes->setValuationMeet($this);
        $this->attributes[] = $attributes;

        return $this;
    }

    /**
     * Remove attributes
     *
     * @param \FormGeneratorBundle\Entity\ValuationAttribute $attributes
     */
    public function removeAttribute(\FormGeneratorBundle\Entity\ValuationAttribute $attributes)
    {
        $this->attributes->removeElement($attributes);
    }

    /**
     * Get attributes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAttributes()
    {
        return $this->attributes;
    }


    /**
     * Set status
     *
     * @param \FormGeneratorBundle\Entity\Status $status
     * @return ValuationMeet
     */
    public function setStatus(\FormGeneratorBundle\Entity\Status $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \FormGeneratorBundle\Entity\Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set assessor
     *
     * @param \UserBundle\Entity\User $assessor
     * @return ValuationMeet
     */
    public function setAssessor(\UserBundle\Entity\User $assessor = null)
    {
        $this->assessor = $assessor;

        return $this;
    }

    /**
     * Get assessor
     *
     * @return \UserBundle\Entity\User
     */
    public function getAssessor()
    {
        return $this->assessor;
    }

    /**
     * Set assessed
     *
     * @param \UserBundle\Entity\User $assessed
     * @return ValuationMeet
     */
    public function setAssessed(\UserBundle\Entity\User $assessed = null)
    {
        $this->assessed = $assessed;

        return $this;
    }

    /**
     * Get assessed
     *
     * @return \UserBundle\Entity\User
     */
    public function getAssessed()
    {
        return $this->assessed;
    }

    /**
     * Add skills
     *
     * @param \FormGeneratorBundle\Entity\Skill $skills
     * @return ValuationMeet
     */
    public function addSkill(\FormGeneratorBundle\Entity\Skill $skills)
    {
        $skills->setValuationMeet($this);
        $this->skills[] = $skills;

        return $this;
    }

    /**
     * Remove skills
     *
     * @param \FormGeneratorBundle\Entity\Skill $skills
     */
    public function removeSkill(\FormGeneratorBundle\Entity\Skill $skills)
    {
        $this->skills->removeElement($skills);
    }

    /**
     * Get skills
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * Set meetDate
     *
     * @param \DateTime $meetDate
     * @return ValuationMeet
     */
    public function setMeetDate($meetDate)
    {
        $this->meetDate = $meetDate;

        return $this;
    }

    /**
     * Get meetDate
     *
     * @return \DateTime 
     */
    public function getMeetDate()
    {
        return $this->meetDate;
    }
}
