<?php

namespace AppBundle\Entity;

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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ValuationMeet", mappedBy="status", cascade={"persist"})
     */
    private $valuationMeets;


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
    }

    /**
     * Add valuationMeets
     *
     * @param \AppBundle\Entity\ValuationMeet $valuationMeets
     * @return Status
     */
    public function addValuationMeet(\AppBundle\Entity\ValuationMeet $valuationMeets)
    {
        $this->valuationMeets[] = $valuationMeets;

        return $this;
    }

    /**
     * Remove valuationMeets
     *
     * @param \AppBundle\Entity\ValuationMeet $valuationMeets
     */
    public function removeValuationMeet(\AppBundle\Entity\ValuationMeet $valuationMeets)
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
}
