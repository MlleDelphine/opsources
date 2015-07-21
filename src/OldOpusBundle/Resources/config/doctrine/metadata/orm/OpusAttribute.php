<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * OpusAttribute
 *
 * @ORM\Table(name="opus_attribute", indexes={@ORM\Index(name="opus_attribute_collection_id", columns={"collection_id"}), @ORM\Index(name="opus_attribute_sheet_id", columns={"sheet_id"})})
 * @ORM\Entity
 */
class OpusAttribute
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="opus_attribute_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=64, nullable=false)
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text", nullable=true)
     */
    private $value;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="value_date", type="datetime", nullable=true)
     */
    private $valueDate;

    /**
     * @var string
     *
     * @ORM\Column(name="value_data", type="blob", nullable=true)
     */
    private $valueData;

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
     * @var string
     *
     * @ORM\Column(name="value_base64", type="text", nullable=true)
     */
    private $valueBase64;

    /**
     * @var \OpusSheet
     *
     * @ORM\ManyToOne(targetEntity="OpusSheet")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sheet_id", referencedColumnName="id")
     * })
     */
    private $sheet;

    /**
     * @var \OpusCollection
     *
     * @ORM\ManyToOne(targetEntity="OpusCollection")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="collection_id", referencedColumnName="id")
     * })
     */
    private $collection;


}
