<?php

namespace GeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OpusSheetTemplate
 *
 * @ORM\Table()
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
     * @ORM\OneToMany(targetEntity="GeneratorBundle\Entity\OpusInfo", mappedBy="opusTemplate", cascade={"persist"})
     */

    private $opusInfos;

    /**
     * @ORM\OneToMany(targetEntity="GeneratorBundle\Entity\OpusSheet", mappedBy="opusTemplate", cascade={"persist"})
     */

    private $opusSheets;


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
        $this->opusInfos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add opusInfos
     *
     * @param \GeneratorBundle\Entity\OpusInfo $opusInfos
     * @return OpusSheetTemplate
     */
    public function addOpusInfo(\GeneratorBundle\Entity\OpusInfo $opusInfos)
    {
        $this->opusInfos[] = $opusInfos;

        return $this;
    }

    /**
     * Remove opusInfos
     *
     * @param \GeneratorBundle\Entity\OpusInfo $opusInfos
     */
    public function removeOpusInfo(\GeneratorBundle\Entity\OpusInfo $opusInfos)
    {
        $this->opusInfos->removeElement($opusInfos);
    }

    /**
     * Get opusInfos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOpusInfos()
    {
        return $this->opusInfos;
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
}
