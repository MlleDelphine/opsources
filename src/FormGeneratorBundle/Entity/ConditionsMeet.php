<?php

namespace FormGeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ConditionsMeet
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FormGeneratorBundle\Entity\Repository\ConditionsMeetRepository")
 */
class ConditionsMeet
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
     * @ORM\ManyToOne(targetEntity="FormGeneratorBundle\Entity\Status", inversedBy="conditionsMeets", cascade={"persist"})
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="assessorConditionsMeets", cascade={"persist"})
     */
    private $assessor;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="assessedConditionsMeets", cascade={"persist"})
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
     * @ORM\OneToMany(targetEntity="FormGeneratorBundle\Entity\ConditionsAttribute", mappedBy="conditionsMeet", cascade={"remove", "persist"})
     */
    private $attributes;

    /**
     * @ORM\OneToMany(targetEntity="FormGeneratorBundle\Entity\WorkCondition", mappedBy="conditionsMeet", cascade={"remove", "persist"})
     */
    private $workConditions;


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
     * @return ConditionsMeet
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
     * @return ConditionsMeet
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
     * @return ConditionsMeet
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
    }

    /**
     * Add attributes
     *
     * @param \FormGeneratorBundle\Entity\ConditionsAttribute $attributes
     * @return ConditionsMeet
     */
    public function addAttribute(\FormGeneratorBundle\Entity\ConditionsAttribute $attributes)
    {
        $attributes->setConditionsMeet($this);
        $this->attributes[] = $attributes;

        return $this;
    }

    /**
     * Remove attributes
     *
     * @param \FormGeneratorBundle\Entity\ConditionsAttribute $attributes
     */
    public function removeAttribute(\FormGeneratorBundle\Entity\ConditionsAttribute $attributes)
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
     * @return ConditionsMeet
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
     * @return ConditionsMeet
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
     * @return ConditionsMeet
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
     * Add workConditions
     *
     * @param \FormGeneratorBundle\Entity\WorkCondition $workConditions
     * @return ConditionsMeet
     */
    public function addWorkCondition(\FormGeneratorBundle\Entity\WorkCondition $workConditions)
    {
        $workConditions->setConditionsMeet($this);
        $this->workConditions[] = $workConditions;

        return $this;
    }

    /**
     * Remove workConditions
     *
     * @param \FormGeneratorBundle\Entity\WorkCondition $workConditions
     */
    public function removeWorkCondition(\FormGeneratorBundle\Entity\WorkCondition $workConditions)
    {
        $this->workConditions->removeElement($workConditions);
    }

    /**
     * Get workConditions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWorkConditions()
    {
        return $this->workConditions;
    }

    /**
     * Set meetDate
     *
     * @param \DateTime $meetDate
     * @return ConditionsMeet
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
