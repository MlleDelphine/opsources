<?php

namespace OldOpusBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OpusSheet
 *
 * @ORM\Table(name="opus_sheet", indexes={@ORM\Index(name="opus_sheet_info_id", columns={"info_id"}), @ORM\Index(name="opus_sheet_evaluate_id", columns={"evaluate_id"}), @ORM\Index(name="opus_sheet_job1_id", columns={"job1_id"}), @ORM\Index(name="opus_sheet_job2_id", columns={"job2_id"})})
 * @ORM\Entity
 */
class OpusSheet
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="opus_sheet_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="evaluator_id", type="bigint", nullable=true)
     */
    private $evaluatorId;

    /**
     * @var integer
     *
     * @ORM\Column(name="superior_id", type="bigint", nullable=true)
     */
    private $superiorId;

    /**
     * @var integer
     *
     * @ORM\Column(name="director_id", type="bigint", nullable=true)
     */
    private $directorId;

    /**
     * @var integer
     *
     * @ORM\Column(name="responsable_id", type="bigint", nullable=true)
     */
    private $responsableId;

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
     * @var \OpusJob
     *
     * @ORM\ManyToOne(targetEntity="OpusJob")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="job2_id", referencedColumnName="id")
     * })
     */
    private $job2;

    /**
     * @var \OpusJob
     *
     * @ORM\ManyToOne(targetEntity="OpusJob")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="job1_id", referencedColumnName="id")
     * })
     */
    private $job1;

    /**
     * @var \OpusInfo
     *
     * @ORM\ManyToOne(targetEntity="OpusInfo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="info_id", referencedColumnName="id")
     * })
     */
    private $info;

    /**
     * @var \OpusUsers
     *
     * @ORM\ManyToOne(targetEntity="OpusUsers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="evaluate_id", referencedColumnName="id")
     * })
     */
    private $evaluate;



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
     * Set evaluatorId
     *
     * @param integer $evaluatorId
     * @return OpusSheet
     */
    public function setEvaluatorId($evaluatorId)
    {
        $this->evaluatorId = $evaluatorId;

        return $this;
    }

    /**
     * Get evaluatorId
     *
     * @return integer 
     */
    public function getEvaluatorId()
    {
        return $this->evaluatorId;
    }

    /**
     * Set superiorId
     *
     * @param integer $superiorId
     * @return OpusSheet
     */
    public function setSuperiorId($superiorId)
    {
        $this->superiorId = $superiorId;

        return $this;
    }

    /**
     * Get superiorId
     *
     * @return integer 
     */
    public function getSuperiorId()
    {
        return $this->superiorId;
    }

    /**
     * Set directorId
     *
     * @param integer $directorId
     * @return OpusSheet
     */
    public function setDirectorId($directorId)
    {
        $this->directorId = $directorId;

        return $this;
    }

    /**
     * Get directorId
     *
     * @return integer 
     */
    public function getDirectorId()
    {
        return $this->directorId;
    }

    /**
     * Set responsableId
     *
     * @param integer $responsableId
     * @return OpusSheet
     */
    public function setResponsableId($responsableId)
    {
        $this->responsableId = $responsableId;

        return $this;
    }

    /**
     * Get responsableId
     *
     * @return integer 
     */
    public function getResponsableId()
    {
        return $this->responsableId;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return OpusSheet
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
     * @return OpusSheet
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
     * @return OpusSheet
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
     * Set job2
     *
     * @param \OldOpusBundle\Entity\OpusJob $job2
     * @return OpusSheet
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

    /**
     * Set job1
     *
     * @param \OldOpusBundle\Entity\OpusJob $job1
     * @return OpusSheet
     */
    public function setJob1(\OldOpusBundle\Entity\OpusJob $job1 = null)
    {
        $this->job1 = $job1;

        return $this;
    }

    /**
     * Get job1
     *
     * @return \OldOpusBundle\Entity\OpusJob 
     */
    public function getJob1()
    {
        return $this->job1;
    }

    /**
     * Set info
     *
     * @param \OldOpusBundle\Entity\OpusInfo $info
     * @return OpusSheet
     */
    public function setInfo(\OldOpusBundle\Entity\OpusInfo $info = null)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Get info
     *
     * @return \OldOpusBundle\Entity\OpusInfo 
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Set evaluate
     *
     * @param \OldOpusBundle\Entity\OpusUsers $evaluate
     * @return OpusSheet
     */
    public function setEvaluate(\OldOpusBundle\Entity\OpusUsers $evaluate = null)
    {
        $this->evaluate = $evaluate;

        return $this;
    }

    /**
     * Get evaluate
     *
     * @return \OldOpusBundle\Entity\OpusUsers 
     */
    public function getEvaluate()
    {
        return $this->evaluate;
    }
}
