<?php

namespace FormGeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SkillState
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class SkillState
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
     * @ORM\OneToMany(targetEntity="FormGeneratorBundle\Entity\Skill", mappedBy="state", cascade={"persist"})
     */
    private $skill;


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
     * @return SkillState
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
        $this->skill = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add skill
     *
     * @param \FormGeneratorBundle\Entity\Skill $skill
     * @return SkillState
     */
    public function addSkill(\FormGeneratorBundle\Entity\Skill $skill)
    {
        $this->skill[] = $skill;

        return $this;
    }

    /**
     * Remove skill
     *
     * @param \FormGeneratorBundle\Entity\Skill $skill
     */
    public function removeSkill(\FormGeneratorBundle\Entity\Skill $skill)
    {
        $this->skill->removeElement($skill);
    }

    /**
     * Get skill
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSkill()
    {
        return $this->skill;
    }
}
