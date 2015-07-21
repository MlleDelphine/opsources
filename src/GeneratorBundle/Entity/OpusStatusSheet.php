<?php

namespace GeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OpusStatusSheet
 *
 * @ORM\Table(name="opus_status_sheet")
 * @ORM\Entity(repositoryClass="FormGeneratorBundle\Entity\Repository\OpusStatusSheetRepository")
 */
class OpusStatusSheet
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
     * @ORM\Column(name="label", type="string", length=255)
     */
    private $label;

    /**
     * @var integer
     *
     * @ORM\Column(name="intCode", type="integer")
     */
    private $intCode;

    /**
     * @var string
     *
     * @ORM\Column(name="strCode", type="string", length=255)
     */
    private $strCode;


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
     * @return OpusStatusSheet
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
     * Set intCode
     *
     * @param integer $intCode
     * @return OpusStatusSheet
     */
    public function setIntCode($intCode)
    {
        $this->intCode = $intCode;

        return $this;
    }

    /**
     * Get intCode
     *
     * @return integer 
     */
    public function getIntCode()
    {
        return $this->intCode;
    }

    /**
     * Set strCode
     *
     * @param string $strCode
     * @return OpusStatusSheet
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
}
