<?php

namespace GeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * OpusJob
 *
 * @ORM\Table(name="opus_job")
 * @ORM\Entity
 */
class OpusJob
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="opus_job_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="text", nullable=false, length=768)
     */
    private $label;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="bigint", nullable=false)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\User", mappedBy="job", cascade={"persist"})
     */
    private $jobFirst;

    /**
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\User", mappedBy="job2", cascade={"persist"})
     */
    private $jobSecond;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set label
     *
     * @param string $label
     * @return OpusJob
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return OpusJob
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
     * @return OpusJob
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
     * @return OpusJob
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
     * Constructor
     */
    public function __construct()
    {
        $this->jobFirst = new \Doctrine\Common\Collections\ArrayCollection();
        $this->jobSecond = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add jobFirst
     *
     * @param \UserBundle\Entity\User $jobFirst
     * @return OpusJob
     */
    public function addJobFirst(\UserBundle\Entity\User $jobFirst)
    {
        $this->jobFirst[] = $jobFirst;

        return $this;
    }

    /**
     * Remove jobFirst
     *
     * @param \UserBundle\Entity\User $jobFirst
     */
    public function removeJobFirst(\UserBundle\Entity\User $jobFirst)
    {
        $this->jobFirst->removeElement($jobFirst);
    }

    /**
     * Get jobFirst
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJobFirst()
    {
        return $this->jobFirst;
    }

    /**
     * Add jobSecond
     *
     * @param \UserBundle\Entity\User $jobSecond
     * @return OpusJob
     */
    public function addJobSecond(\UserBundle\Entity\User $jobSecond)
    {
        $this->jobSecond[] = $jobSecond;

        return $this;
    }

    /**
     * Remove jobSecond
     *
     * @param \UserBundle\Entity\User $jobSecond
     */
    public function removeJobSecond(\UserBundle\Entity\User $jobSecond)
    {
        $this->jobSecond->removeElement($jobSecond);
    }

    /**
     * Get jobSecond
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJobSecond()
    {
        return $this->jobSecond;
    }
}
