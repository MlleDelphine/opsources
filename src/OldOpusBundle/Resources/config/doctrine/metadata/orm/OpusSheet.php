<?php



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


}
