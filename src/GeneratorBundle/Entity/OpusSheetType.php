<?php

namespace GeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * OpusSheetType
 *
 * @ORM\Table(name="opus_sheet_type")
 * @ORM\Entity(repositoryClass="GeneratorBundle\Entity\Repository\OpusSheetTypeRepository")
 */
class OpusSheetType
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="str_code", type="string", length=255)
     */
    private $strCode;

    /**
     * @ORM\OneToMany(targetEntity="GeneratorBundle\Entity\OpusInfo", mappedBy="type", cascade={"persist"})
     */

    private $opusInfos;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    private $created;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    private $updated;

    public function __toString()
    {
        return (string)$this->getName();
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
     * Set name
     *
     * @param string $name
     * @return OpusSheetType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return OpusSheetType
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return OpusSheetType
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set strCode
     *
     * @param string $strCode
     * @return OpusSheetType
     */
    public function setStrCode($strCode)
    {
        $this->strCode = $strCode;

        return $this;
    }

    /**
     * Get strCode
     *
     * @return string 
     */
    public function getStrCode()
    {
        return $this->strCode;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->opusSheets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add opusSheets
     *
     * @param \GeneratorBundle\Entity\OpusInfo $opusSheets
     * @return OpusSheetType
     */
    public function addOpusSheet(\GeneratorBundle\Entity\OpusInfo $opusSheets)
    {
        $this->opusSheets[] = $opusSheets;

        return $this;
    }

    /**
     * Remove opusSheets
     *
     * @param \GeneratorBundle\Entity\OpusInfo $opusSheets
     */
    public function removeOpusSheet(\GeneratorBundle\Entity\OpusInfo $opusSheets)
    {
        $this->opusSheets->removeElement($opusSheets);
    }

    /**
     * Get opusSheets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOpusSheets()
    {
        return $this->opusSheets;
    }
}
