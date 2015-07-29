<?php

namespace GeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * OpusCollection
 *
 * @ORM\Table(name="opus_collection", indexes={@ORM\Index(name="opus_collection_sheet_id", columns={"sheet_id"}), @ORM\Index(name="opus_collection_users_id", columns={"users_id"})})
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
     * @var \OpusSheet
     *
     * @ORM\ManyToOne(targetEntity="GeneratorBundle\Entity\OpusSheet")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sheet_id", referencedColumnName="id")
     * })
     */
    private $sheet;


    /**
     * @ORM\OneToMany(targetEntity="GeneratorBundle\Entity\OpusAttribute", mappedBy="collection", cascade={"persist"})
     */
    private $attributes;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attributes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set usersId
     *
     * @param integer $usersId
     * @return OpusCollection
     */
    public function setUsersId($usersId)
    {
        $this->usersId = $usersId;

        return $this;
    }

    /**
     * Get usersId
     *
     * @return integer 
     */
    public function getUsersId()
    {
        return $this->usersId;
    }

    /**
     * Set infoId
     *
     * @param integer $infoId
     * @return OpusCollection
     */
    public function setInfoId($infoId)
    {
        $this->infoId = $infoId;

        return $this;
    }

    /**
     * Get infoId
     *
     * @return integer 
     */
    public function getInfoId()
    {
        return $this->infoId;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return OpusCollection
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set location
     *
     * @param integer $location
     * @return OpusCollection
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return integer 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return OpusCollection
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
     * @return OpusCollection
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
     * Set sheet
     *
     * @param \GeneratorBundle\Entity\OpusSheet $sheet
     * @return OpusCollection
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
     * Add attributes
     *
     * @param \GeneratorBundle\Entity\OpusAttribute $attributes
     * @return OpusCollection
     */
    public function addAttribute(\GeneratorBundle\Entity\OpusAttribute $attributes)
    {
        $this->attributes[] = $attributes;
        $attributes->setCollection($this);

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
}
