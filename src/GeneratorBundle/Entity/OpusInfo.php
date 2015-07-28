<?php

namespace GeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * OpusInfo
 *
 * @ORM\Table(name="opus_info")
 * @ORM\Entity(repositoryClass="GeneratorBundle\Entity\Repository\OpusInfoRepository")
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
     * @var \OpusSheetType
     * @ORM\ManyToOne(targetEntity="GeneratorBundle\Entity\OpusSheetType", inversedBy="opusInfos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     * })
     */

    private $type;


    /**
     * @var \OpusSheetTemplate
     * @ORM\ManyToOne(targetEntity="GeneratorBundle\Entity\OpusSheetTemplate", inversedBy="opusInfos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="template_id", referencedColumnName="id")
     * })
     */

    private $opusTemplate;



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
     * Set year
     *
     * @param integer $year
     * @return OpusInfo
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set template
     *
     * @param string $template
     * @return OpusInfo
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set mailDate
     *
     * @param \DateTime $mailDate
     * @return OpusInfo
     */
    public function setMailDate($mailDate)
    {
        $this->mailDate = $mailDate;

        return $this;
    }

    /**
     * Get mailDate
     *
     * @return \DateTime
     */
    public function getMailDate()
    {
        return $this->mailDate;
    }

    /**
     * Set limitDate
     *
     * @param \DateTime $limitDate
     * @return OpusInfo
     */
    public function setLimitDate($limitDate)
    {
        $this->limitDate = $limitDate;

        return $this;
    }

    /**
     * Get limitDate
     *
     * @return \DateTime
     */
    public function getLimitDate()
    {
        return $this->limitDate;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return OpusInfo
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return OpusInfo
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
     * @return OpusInfo
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
     * Set confFile
     *
     * @param string $confFile
     * @return OpusInfo
     */
    public function setConfFile($confFile)
    {
        $this->confFile = $confFile;

        return $this;
    }

    /**
     * Get confFile
     *
     * @return string 
     */
    public function getConfFile()
    {
        return $this->confFile;
    }

    /**
     * Set type
     *
     * @param \GeneratorBundle\Entity\OpusSheetType $type
     * @return OpusInfo
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
     * Set confFileUi
     *
     * @param string $confFileUi
     * @return OpusInfo
     */
    public function setConfFileUi($confFileUi)
    {
        $this->confFileUi = $confFileUi;

        return $this;
    }

    /**
     * Get confFileUi
     *
     * @return string 
     */
    public function getConfFileUi()
    {
        return $this->confFileUi;
    }

    /**
     * Set opusTemplate
     *
     * @param \GeneratorBundle\Entity\OpusSheetTemplate $opusTemplate
     * @return OpusInfo
     */
    public function setOpusTemplate(\GeneratorBundle\Entity\OpusSheetTemplate $opusTemplate = null)
    {
        $this->opusTemplate = $opusTemplate;

        return $this;
    }

    /**
     * Get opusTemplate
     *
     * @return \GeneratorBundle\Entity\OpusSheetTemplate 
     */
    public function getOpusTemplate()
    {
        return $this->opusTemplate;
    }
}
