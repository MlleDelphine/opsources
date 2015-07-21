<?php

namespace GeneratorBundle\Entity;

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
     *
     * @ORM\JoinColumn(name="evaluator_id", referencedColumnName="id")
     *
     */
    private $evaluatorId;

    /**
     *
     * @ORM\JoinColumn(name="superior_id", referencedColumnName="id")
     *
     */
    private $superiorId;

    /**
     *
     * @ORM\JoinColumn(name="superior_id", referencedColumnName="id")
     *
     */
    private $directorId;

    /**
     *
     * @ORM\JoinColumn(name="responsable_id", referencedColumnName="id")
     *
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
     * @ORM\ManyToOne(targetEntity="GeneratorBundle\Entity\OpusJob")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="job2_id", referencedColumnName="id")
     * })
     */
    private $job2;

    /**
     * @var \OpusJob
     *
     * @ORM\ManyToOne(targetEntity="GeneratorBundle\Entity\OpusJob")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="job1_id", referencedColumnName="id")
     * })
     */
    private $job1;

    /**
     * @var \OpusInfo
     *
     * @ORM\ManyToOne(targetEntity="GeneratorBundle\Entity\OpusInfo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="info_id", referencedColumnName="id")
     * })
     */
    private $info;

    /**
     * @var \OpusUsers
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="opusSheetsEvaluate", cascade={"persist"})
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
     * @param \GeneratorBundle\Entity\OpusJob $job2
     * @return OpusSheet
     */
    public function setJob2(\GeneratorBundle\Entity\OpusJob $job2 = null)
    {
        $this->job2 = $job2;

        return $this;
    }

    /**
     * Get job2
     *
     * @return \GeneratorBundle\Entity\OpusJob 
     */
    public function getJob2()
    {
        return $this->job2;
    }

    /**
     * Set job1
     *
     * @param \GeneratorBundle\Entity\OpusJob $job1
     * @return OpusSheet
     */
    public function setJob1(\GeneratorBundle\Entity\OpusJob $job1 = null)
    {
        $this->job1 = $job1;

        return $this;
    }

    /**
     * Get job1
     *
     * @return \GeneratorBundle\Entity\OpusJob 
     */
    public function getJob1()
    {
        return $this->job1;
    }

    /**
     * Set info
     *
     * @param \GeneratorBundle\Entity\OpusInfo $info
     * @return OpusSheet
     */
    public function setInfo(\GeneratorBundle\Entity\OpusInfo $info = null)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Get info
     *
     * @return \GeneratorBundle\Entity\OpusInfo 
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Set evaluate
     *
     * @param \UserBundle\Entity\OpusUsers $evaluate
     * @return OpusSheet
     */
    public function setEvaluate(\UserBundle\Entity\User $evaluate = null)
    {
        $this->evaluate = $evaluate;

        return $this;
    }

    /**
     * Get evaluate
     *
     * @return \UserBundle\Entity\User
     */
    public function getEvaluate()
    {
        return $this->evaluate;
    }
}
