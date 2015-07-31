<?php

namespace GeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OpusSheetTemplate
 *
 * @ORM\Table(name="opus_sheet_template")
 * @ORM\Entity
 */
class OpusSheetTemplate
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
     * @var \OpusSheetType
     * @ORM\ManyToOne(targetEntity="GeneratorBundle\Entity\OpusSheetType", inversedBy="opusTemplates")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     * })
     */

    private $type;

    /**
     * @var \Media
     * @ORM\OneToOne(targetEntity="MediaBundle\Entity\Media", inversedBy="template", cascade={"persist", "remove"})
     */
    private $confFile;

    /**
     * @ORM\OneToMany(targetEntity="GeneratorBundle\Entity\OpusCampaign", mappedBy="opusTemplate", cascade={"persist"})
     */

    private $campaigns;

    /**
     * @ORM\OneToMany(targetEntity="GeneratorBundle\Entity\OpusSheet", mappedBy="opusTemplate", cascade={"persist"})
     */

    private $opusSheets;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="bigint", nullable=true)
     */
    private $status = '0';


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
     * @return OpusSheetTemplate
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
     * Constructor
     */
    public function __construct()
    {

        $this->campaigns = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add campaigns
     *
     * @param \GeneratorBundle\Entity\OpusCampaign $campaigns
     * @return OpusSheetTemplate
     */
    public function addCampaign(\GeneratorBundle\Entity\OpusCampaign $campaigns)
    {
        $this->campaigns[] = $campaigns;

        return $this;
    }

    /**
     * Remove campaigns
     *
     * @param \GeneratorBundle\Entity\opusCampaign $campaigns
     */
    public function removeCampaign(\GeneratorBundle\Entity\OpusCampaign $campaigns)
    {
        $this->campaigns->removeElement($campaigns);
    }

    /**
     * Get campaigns
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCampaigns()
    {
        return $this->campaigns;
    }

    /**
     * Add opusSheets
     *
     * @param \GeneratorBundle\Entity\OpusSheet $opusSheets
     * @return OpusSheetTemplate
     */
    public function addOpusSheet(\GeneratorBundle\Entity\OpusSheet $opusSheets)
    {
        $this->opusSheets[] = $opusSheets;

        return $this;
    }

    /**
     * Remove opusSheets
     *
     * @param \GeneratorBundle\Entity\OpusSheet $opusSheets
     */
    public function removeOpusSheet(\GeneratorBundle\Entity\OpusSheet $opusSheets)
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

    /**
     * Set status
     *
     * @param integer $status
     * @return OpusSheetTemplate
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
     * Set type
     *
     * @param \GeneratorBundle\Entity\OpusSheetType $type
     * @return OpusSheetTemplate
     */
    public function setType(\GeneratorBundle\Entity\OpusSheetType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \GeneratorBundle\Entity\OpusSheetType 
     */
    public function getType()
    {
        return $this->type;
    }
    

    /**
     * Set confFile
     *
     * @param \MediaBundle\Entity\Media $confFile
     * @return OpusSheetTemplate
     */
    public function setConfFile(\MediaBundle\Entity\Media $confFile = null)
    {
        $this->confFile = $confFile;
        $confFile->setTemplate($this);

        return $this;
    }

    /**
     * Get confFile
     *
     * @return \MediaBundle\Entity\Media 
     */
    public function getConfFile()
    {
        return $this->confFile;
    }

}
