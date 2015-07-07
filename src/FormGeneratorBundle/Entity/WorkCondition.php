<?php

namespace FormGeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WorkCondition
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FormGeneratorBundle\Entity\Repository\WorkConditionRepository")
 */
class WorkCondition
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
     * @ORM\Column(name="evaluation", type="integer")
     */
    private $evaluation;

    /**
     * @var string
     *
     * @ORM\Column(name="comments", type="text")
     */
    private $comments;


    /**
     * @ORM\ManyToOne(targetEntity="FormGeneratorBundle\Entity\ConditionsMeet", inversedBy="workConditions")
     * @ORM\JoinColumn(name="conditionsmeet_id", referencedColumnName="id")
     */
    private $conditionsMeet;


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
     * @return WorkCondition
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
     * Set comments
     *
     * @param string $comments
     * @return WorkCondition
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
     * Set conditionsMeet
     *
     * @param \FormGeneratorBundle\Entity\ConditionsMeet $conditionsMeet
     * @return WorkCondition
     */
    public function setConditionsMeet(\FormGeneratorBundle\Entity\ConditionsMeet $conditionsMeet = null)
    {
        $this->conditionsMeet = $conditionsMeet;

        return $this;
    }

    /**
     * Get conditionsMeet
     *
     * @return \FormGeneratorBundle\Entity\ConditionsMeet 
     */
    public function getConditionsMeet()
    {
        return $this->conditionsMeet;
    }

    /**
     * Set evaluation
     *
     * @param integer $evaluation
     * @return WorkCondition
     */
    public function setEvaluation($evaluation)
    {
        $this->evaluation = $evaluation;

        return $this;
    }

    /**
     * Get evaluation
     *
     * @return integer 
     */
    public function getEvaluation()
    {
        return $this->evaluation;
    }
}
