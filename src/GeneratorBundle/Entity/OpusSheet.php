<?php

namespace GeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

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
     * @var \OpusUsers
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="opusSheetsEvaluate", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="evaluate_id", referencedColumnName="id")
     * })
     */
    private $evaluate;

    /**
     *
     * @var \OpusUsers
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="opusSheetsEvaluator", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="evaluator_id", referencedColumnName="id", nullable=true)
     * })
     *
     *
     */
    private $evaluator;

    /**
     *
    @var \OpusUsers
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="opusSheetsSuperior", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="superior_id", referencedColumnName="id", nullable=true)
     * })
     *
     */
    private $superior;

    /**
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="opusSheetsDirector", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="director_id", referencedColumnName="id", nullable=true)
     * })
     *
     *
     */
    private $director;

    /**
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="opusSheetsDirector", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="responsable_id", referencedColumnName="id", nullable=true)
     * })
     *
     *
     */
    private $responsable;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="GeneratorBundle\Entity\OpusSheetStatus", inversedBy="opusSheets", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="status", referencedColumnName="intCode", nullable=true)
     * })
     */
    private $status;

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
     * @ORM\OneToMany(targetEntity="GeneratorBundle\Entity\OpusAttribute", mappedBy="sheet", cascade={"persist"})
     */

    private $attributes;

    /**
     * @ORM\OneToMany(targetEntity="GeneratorBundle\Entity\OpusCollection", mappedBy="sheet", cascade={"persist"})
     */

    private $collections;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attributes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->collections = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set evaluator
     *
     * @param integer $evaluator
     * @return OpusSheet
     */
    public function setEvaluator($evaluator)
    {
        $this->evaluator = $evaluator;

        return $this;
    }

    /**
     * Get evaluator
     *
     * @return integer
     */
    public function getEvaluator()
    {
        return $this->evaluator;
    }

    /**
     * Set superior
     *
     * @param integer $superior
     * @return OpusSheet
     */
    public function setSuperior($superior)
    {
        $this->superior = $superior;

        return $this;
    }

    /**
     * Get superior
     *
     * @return integer
     */
    public function getSuperior()
    {
        return $this->superior;
    }

    /**
     * Set director
     *
     * @param integer $director
     * @return OpusSheet
     */
    public function setDirector($director)
    {
        $this->director = $director;

        return $this;
    }

    /**
     * Get director
     *
     * @return integer
     */
    public function getDirector()
    {
        return $this->director;
    }

    /**
     * Set responsable
     *
     * @param integer $responsable
     * @return OpusSheet
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

    /**
     * Set status
     *
     * @param \GeneratorBundle\Entity\OpusSheetStatus $status
     * @return OpusSheet
     */
    public function setStatus(\GeneratorBundle\Entity\OpusSheetStatus $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \GeneratorBundle\Entity\OpusSheetStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add attributes
     *
     * @param \GeneratorBundle\Entity\OpusAttribute $attributes
     * @return OpusSheet
     */
    public function addAttribute(\GeneratorBundle\Entity\OpusAttribute $attributes)
    {
        $this->attributes[] = $attributes;
        $attributes->setSheet($this);

        return $this;
    }

    /**
     * Remove attributes
     *
     * @param \GeneratorBundle\Entity\OpusAttribute $attributes
     */
    public function removeAttribute(\GeneratorBundle\Entity\OpusAttribute $attributes)
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
     * Add collections
     *
     * @param \GeneratorBundle\Entity\OpusCollection $collections
     * @return OpusSheet
     */
    public function addCollection(\GeneratorBundle\Entity\OpusCollection $collections)
    {
        $this->collections[] = $collections;
        $collections->setSheet($this);

        return $this;
    }

    /**
     * Remove collections
     *
     * @param \GeneratorBundle\Entity\OpusCollection $collections
     */
    public function removeCollection(\GeneratorBundle\Entity\OpusCollection $collections)
    {
        $this->collections->removeElement($collections);
    }

    /**
     * Get collections
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCollections()
    {
        return $this->collections;
    }
}
