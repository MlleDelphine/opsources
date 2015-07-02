<?php

namespace FormGeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Status
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Status
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
     * @ORM\OneToMany(targetEntity="FormGeneratorBundle\Entity\ValuationMeet", mappedBy="status", cascade={"persist"})
     */
    private $valuationMeets;

    /**
     * @ORM\OneToMany(targetEntity="FormGeneratorBundle\Entity\ProfessionalMeet", mappedBy="status", cascade={"persist"})
     */
    private $professionalMeets;

    /**
     * @ORM\OneToMany(targetEntity="FormGeneratorBundle\Entity\ConditionsMeet", mappedBy="status", cascade={"persist"})
     */
    private $conditionsMeets;


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
     * @return Status
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
        $this->valuationMeets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->professionalMeets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->conditionsMeets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add valuationMeets
     *
     * @param \FormGeneratorBundle\Entity\ValuationMeet $valuationMeets
     * @return Status
     */
    public function addValuationMeet(\FormGeneratorBundle\Entity\ValuationMeet $valuationMeets)
    {
        $this->valuationMeets[] = $valuationMeets;

        return $this;
    }

    /**
     * Remove valuationMeets
     *
     * @param \FormGeneratorBundle\Entity\ValuationMeet $valuationMeets
     */
    public function removeValuationMeet(\FormGeneratorBundle\Entity\ValuationMeet $valuationMeets)
    {
        $this->valuationMeets->removeElement($valuationMeets);
    }

    /**
     * Get valuationMeets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getValuationMeets()
    {
        return $this->valuationMeets;
    }

    /**
     * Add professionalMeets
     *
     * @param \FormGeneratorBundle\Entity\ProfessionalMeet $professionalMeets
     * @return Status
     */
    public function addProfessionalMeet(\FormGeneratorBundle\Entity\ProfessionalMeet $professionalMeets)
    {
        $this->professionalMeets[] = $professionalMeets;

        return $this;
    }

    /**
     * Remove professionalMeets
     *
     * @param \FormGeneratorBundle\Entity\ProfessionalMeet $professionalMeets
     */
    public function removeProfessionalMeet(\FormGeneratorBundle\Entity\ProfessionalMeet $professionalMeets)
    {
        $this->professionalMeets->removeElement($professionalMeets);
    }

    /**
     * Get professionalMeets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProfessionalMeets()
    {
        return $this->professionalMeets;
    }

    /**
     * Add conditionsMeets
     *
     * @param \FormGeneratorBundle\Entity\ConditionsMeet $conditionsMeets
     * @return Status
     */
    public function addConditionsMeet(\FormGeneratorBundle\Entity\ConditionsMeet $conditionsMeets)
    {
        $this->conditionsMeets[] = $conditionsMeets;

        return $this;
    }

    /**
     * Remove conditionsMeets
     *
     * @param \FormGeneratorBundle\Entity\ConditionsMeet $conditionsMeets
     */
    public function removeConditionsMeet(\FormGeneratorBundle\Entity\ConditionsMeet $conditionsMeets)
    {
        $this->conditionsMeets->removeElement($conditionsMeets);
    }

    /**
     * Get conditionsMeets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getConditionsMeets()
    {
        return $this->conditionsMeets;
    }
}
