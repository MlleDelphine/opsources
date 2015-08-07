<?php

namespace GeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * OpusCampaign.
 *
 * @ORM\Table(name="opus_campaign")
 * @ORM\Entity(repositoryClass="GeneratorBundle\Entity\Repository\OpusCampaignRepository")
 */
class OpusCampaign
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="opus_info_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var int
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
     * @var \DateTime
     *
     * @ORM\Column(name="until_sheet_date", type="datetime", nullable=true)
     */
    private $untilSheetDate;



    /**
     * @var int
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
     * @ORM\ManyToOne(targetEntity="GeneratorBundle\Entity\OpusSheetType", inversedBy="OpusCampaigns")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     * })
     */
    private $type;

    /**
     * @var \OpusSheetTemplate
     * @ORM\ManyToOne(targetEntity="GeneratorBundle\Entity\OpusSheetTemplate", inversedBy="OpusCampaigns")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="template_id", referencedColumnName="id")
     * })
     */
    private $opusTemplate;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set year.
     *
     * @param int $year
     *
     * @return OpusCampaign
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year.
     *
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set template.
     *
     * @param string $template
     *
     * @return OpusCampaign
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template.
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set mailDate.
     *
     * @param \DateTime $mailDate
     *
     * @return OpusCampaign
     */
    public function setMailDate($mailDate)
    {
        $this->mailDate = $mailDate;

        return $this;
    }

    /**
     * Get mailDate.
     *
     * @return \DateTime
     */
    public function getMailDate()
    {
        return $this->mailDate;
    }

    /**
     * Set limitDate.
     *
     * @param \DateTime $limitDate
     *
     * @return OpusCampaign
     */
    public function setLimitDate($limitDate)
    {
        $this->limitDate = $limitDate;

        return $this;
    }

    /**
     * Get limitDate.
     *
     * @return \DateTime
     */
    public function getLimitDate()
    {
        return $this->limitDate;
    }

    /**
     * Set untilSheetDate.
     *
     * @param \DateTime $untilSheetDate
     *
     * @return OpusCampaign
     */
    public function setUntilSheetDate($untilSheetDate)
    {
        $this->untilSheetDate = $untilSheetDate;

        return $this;
    }

    /**
     * Get untilSheetDate.
     *
     * @return \DateTime
     */
    public function getUntilSheetDate()
    {
        return $this->untilSheetDate;
    }

    /**
     * Set status.
     *
     * @param int $status
     *
     * @return OpusCampaign
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return OpusCampaign
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return OpusCampaign
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set type.
     *
     * @param \GeneratorBundle\Entity\OpusSheetType $type
     *
     * @return OpusCampaign
     */
    public function setType(\GeneratorBundle\Entity\OpusSheetType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return \GeneratorBundle\Entity\OpusSheetType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set opusTemplate.
     *
     * @param \GeneratorBundle\Entity\OpusSheetTemplate $opusTemplate
     *
     * @return OpusCampaign
     */
    public function setOpusTemplate(\GeneratorBundle\Entity\OpusSheetTemplate $opusTemplate = null)
    {
        $this->opusTemplate = $opusTemplate;

        return $this;
    }

    /**
     * Get opusTemplate.
     *
     * @return \GeneratorBundle\Entity\OpusSheetTemplate
     */
    public function getOpusTemplate()
    {
        return $this->opusTemplate;
    }
}
