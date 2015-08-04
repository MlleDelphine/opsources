<?php

namespace GeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OpusStatusSheet.
 *
 * @ORM\Table(name="opus_sheet_status")
 * @ORM\Entity(repositoryClass="GeneratorBundle\Entity\Repository\OpusSheetStatusRepository")
 */
class OpusSheetStatus
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255)
     */
    private $label;

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(name="intCode", type="integer", unique=true)
     */
    private $intCode;

    /**
     * @var string
     *
     * @ORM\Column(name="strCode", type="string", length=255)
     */
    private $strCode;

    /**
     * @ORM\OneToMany(targetEntity="GeneratorBundle\Entity\OpusSheet", mappedBy="status", cascade={"persist"})
     */
    private $opusSheets;

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
     * Set label.
     *
     * @param string $label
     *
     * @return OpusStatusSheet
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set intCode.
     *
     * @param int $intCode
     *
     * @return OpusStatusSheet
     */
    public function setIntCode($intCode)
    {
        $this->intCode = $intCode;

        return $this;
    }

    /**
     * Get intCode.
     *
     * @return int
     */
    public function getIntCode()
    {
        return $this->intCode;
    }

    /**
     * Set strCode.
     *
     * @param string $strCode
     *
     * @return OpusStatusSheet
     */
    public function setStrCode($strCode)
    {
        $this->strCode = $strCode;

        return $this;
    }

    /**
     * Get strCode.
     *
     * @return string
     */
    public function getStrCode()
    {
        return $this->strCode;
    }
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->opusSheets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add opusSheets.
     *
     * @param \GeneratorBundle\Entity\OpusSheet $opusSheets
     *
     * @return OpusSheetStatus
     */
    public function addOpusSheet(\GeneratorBundle\Entity\OpusSheet $opusSheets)
    {
        $this->opusSheets[] = $opusSheets;

        return $this;
    }

    /**
     * Remove opusSheets.
     *
     * @param \GeneratorBundle\Entity\OpusSheet $opusSheets
     */
    public function removeOpusSheet(\GeneratorBundle\Entity\OpusSheet $opusSheets)
    {
        $this->opusSheets->removeElement($opusSheets);
    }

    /**
     * Get opusSheets.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOpusSheets()
    {
        return $this->opusSheets;
    }
}
