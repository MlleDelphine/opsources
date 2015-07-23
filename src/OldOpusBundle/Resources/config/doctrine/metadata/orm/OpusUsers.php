<?php



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


}
