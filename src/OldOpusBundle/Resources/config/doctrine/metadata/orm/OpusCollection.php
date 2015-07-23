<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * OpusCollection
 *
 * @ORM\Table(name="opus_collection", indexes={@ORM\Index(name="opus_collection_sheet_id", columns={"sheet_id"}), @ORM\Index(name="opus_collection_info_id", columns={"info_id"}), @ORM\Index(name="opus_collection_users_id", columns={"users_id"})})
 * @ORM\Entity
 */
class OpusCollection
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="opus_collection_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="users_id", type="bigint", nullable=true)
     */
    private $usersId;

    /**
     * @var integer
     *
     * @ORM\Column(name="info_id", type="bigint", nullable=true)
     */
    private $infoId;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=64, nullable=false)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="location", type="bigint", nullable=true)
     */
    private $location;

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
     * @var \OpusSheet
     *
     * @ORM\ManyToOne(targetEntity="OpusSheet")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sheet_id", referencedColumnName="id")
     * })
     */
    private $sheet;


}
