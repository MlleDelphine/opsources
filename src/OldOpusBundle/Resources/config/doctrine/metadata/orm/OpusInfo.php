<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * OpusInfo
 *
 * @ORM\Table(name="opus_info")
 * @ORM\Entity
 */
class OpusInfo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="opus_info_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="year", type="bigint", nullable=false)
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="template", type="blob", nullable=true)
     */
    private $template;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="mail_date", type="datetime", nullable=true)
     */
    private $mailDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="limit_date", type="datetime", nullable=true)
     */
    private $limitDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="bigint", nullable=true)
     */
    private $status = '0';

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


}
