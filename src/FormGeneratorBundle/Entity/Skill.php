<?php

namespace FormGeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Skill
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FormGeneratorBundle\Entity\Repository\SkillRepository")
 */
class Skill
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
     * @ORM\Column(name="strongPoint", type="text")
     */
    private $strongPoint;

    /**
     * @var string
     *
     * @ORM\Column(name="comments", type="text")
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="FormGeneratorBundle\Entity\SkillState", inversedBy="skill", cascade={"persist"})
     * @ORM\JoinColumn(name="skill_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity="FormGeneratorBundle\Entity\ValuationMeet", inversedBy="skills")
     * @ORM\JoinColumn(name="valuationmeet_id", referencedColumnName="id")
     */
    private $valuationMeet;


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
     * @return Skill
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
     * Set strongPoint
     *
     * @param string $strongPoint
     * @return Skill
     */
    public function setStrongPoint($strongPoint)
    {
        $this->strongPoint = $strongPoint;

        return $this;
    }

    /**
     * Get strongPoint
     *
     * @return string 
     */
    public function getStrongPoint()
    {
        return $this->strongPoint;
    }

    /**
     * Set comments
     *
     * @param string $comments
     * @return Skill
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return string 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set state
     *
     * @param \FormGeneratorBundle\Entity\Skill $state
     * @return Skill
     */
    public function setState(\FormGeneratorBundle\Entity\Skill $state = null)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return \FormGeneratorBundle\Entity\Skill 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set valuationMeet
     *
     * @param \FormGeneratorBundle\Entity\ValuationMeet $valuationMeet
     * @return Skill
     */
    public function setValuationMeet(\FormGeneratorBundle\Entity\ValuationMeet $valuationMeet = null)
    {
        $this->valuationMeet = $valuationMeet;

        return $this;
    }

    /**
     * Get valuationMeet
     *
     * @return \FormGeneratorBundle\Entity\ValuationMeet 
     */
    public function getValuationMeet()
    {
        return $this->valuationMeet;
    }
}
