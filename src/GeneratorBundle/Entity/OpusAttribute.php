<?php

namespace GeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

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
     * @var string
     *
     * @ORM\Column(name="value_base64", type="text", nullable=true)
     */
    private $valueBase64;

    /**
     * @var \OpusSheet
     *
     * @ORM\ManyToOne(targetEntity="GeneratorBundle\Entity\OpusSheet")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sheet_id", referencedColumnName="id")
     * })
     */
    private $sheet;

    /**
     * @var \OpusCollection
     *
     * @ORM\ManyToOne(targetEntity="GeneratorBundle\Entity\OpusCollection")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="collection_id", referencedColumnName="id")
     * })
     */
    private $collection;



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
     * @return OpusAttribute
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
     * Set value
     *
     * @param string $value
     * @return OpusAttribute
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set valueDate
     *
     * @param \DateTime $valueDate
     * @return OpusAttribute
     */
    public function setValueDate($valueDate)
    {
        $this->valueDate = $valueDate;

        return $this;
    }

    /**
     * Get valueDate
     *
     * @return \DateTime 
     */
    public function getValueDate()
    {
        return $this->valueDate;
    }

    /**
     * Set valueData
     *
     * @param string $valueData
     * @return OpusAttribute
     */
    public function setValueData($valueData)
    {
        $this->valueData = $valueData;

        return $this;
    }

    /**
     * Get valueData
     *
     * @return string 
     */
    public function getValueData()
    {
        return $this->valueData;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return OpusAttribute
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
     * @return OpusAttribute
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
     * Set valueBase64
     *
     * @param string $valueBase64
     * @return OpusAttribute
     */
    public function setValueBase64($valueBase64)
    {
        $this->valueBase64 = $valueBase64;

        return $this;
    }

    /**
     * Get valueBase64
     *
     * @return string 
     */
    public function getValueBase64()
    {
        return $this->valueBase64;
    }

    /**
     * Set sheet
     *
     * @param \GeneratorBundle\Entity\OpusSheet $sheet
     * @return OpusAttribute
     */
    public function setSheet(\GeneratorBundle\Entity\OpusSheet $sheet = null)
    {
        $this->sheet = $sheet;

        return $this;
    }

    /**
     * Get sheet
     *
     * @return \GeneratorBundle\Entity\OpusSheet 
     */
    public function getSheet()
    {
        return $this->sheet;
    }

    /**
     * Set collection
     *
     * @param \GeneratorBundle\Entity\OpusCollection $collection
     * @return OpusAttribute
     */
    public function setCollection(\GeneratorBundle\Entity\OpusCollection $collection = null)
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * Get collection
     *
     * @return \GeneratorBundle\Entity\OpusCollection 
     */
    public function getCollection()
    {
        return $this->collection;
    }
}
