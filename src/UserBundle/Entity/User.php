<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 16/06/2015
 * Time: 11:03
 */
namespace UserBundle\Entity;

use Arianespace\PlexcelBundle\Security\User\UserInterface;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="UserBundle\Entity\Repository\UserRepository")
 */
class User extends BaseUser implements UserInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="FormGeneratorBundle\Entity\ValuationMeet", mappedBy="assessor", cascade={"persist"})
     */
    private $assessorValuationMeets;

    /**
     * @ORM\OneToMany(targetEntity="FormGeneratorBundle\Entity\ValuationMeet", mappedBy="assessed", cascade={"persist"})
     */
    private $assessedValuationMeets;

    /**
     * @ORM\OneToMany(targetEntity="FormGeneratorBundle\Entity\ProfessionalMeet", mappedBy="assessor", cascade={"persist"})
     */
    private $assessorProfessionalMeets;

    /**
     * @ORM\OneToMany(targetEntity="FormGeneratorBundle\Entity\ProfessionalMeet", mappedBy="assessed", cascade={"persist"})
     */
    private $assessedProfessionalMeets;



    public function __construct()
    {
        parent::__construct();
        $this->assessorValuationMeets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->assessedValuationMeets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->assessorProfessionalMeets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->assessedProfessionalMeets = new \Doctrine\Common\Collections\ArrayCollection();
        // your own logic
    }

    public function __toString()
    {
        return (string)$this->getUsername();
    }

    public function getDisplayName(){

        return $this->getUsername();
    }

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
     * Add assessorValuationMeets
     *
     * @param \FormGeneratorBundle\Entity\ValuationMeet $assessorValuationMeets
     * @return User
     */
    public function addAssessorValuationMeet(\FormGeneratorBundle\Entity\ValuationMeet $assessorValuationMeets)
    {
        $this->assessorValuationMeets[] = $assessorValuationMeets;
        $assessorValuationMeets->setAssessor($this);

        return $this;
    }

    /**
     * Remove assessorValuationMeets
     *
     * @param \FormGeneratorBundle\Entity\ValuationMeet $assessorValuationMeets
     */
    public function removeAssessorValuationMeet(\FormGeneratorBundle\Entity\ValuationMeet $assessorValuationMeets)
    {
        $this->assessorValuationMeets->removeElement($assessorValuationMeets);
    }

    /**
     * Get assessorValuationMeets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAssessorValuationMeets()
    {
        return $this->assessorValuationMeets;
    }

    /**
     * Add assessedValuationMeets
     *
     * @param \FormGeneratorBundle\Entity\ValuationMeet $assessedValuationMeets
     * @return User
     */
    public function addAssessedValuationMeet(\FormGeneratorBundle\Entity\ValuationMeet $assessedValuationMeets)
    {
        $this->assessedValuationMeets[] = $assessedValuationMeets;
        $assessedValuationMeets->setAssessed($this);

        return $this;
    }

    /**
     * Remove assessedValuationMeets
     *
     * @param \FormGeneratorBundle\Entity\ValuationMeet $assessedValuationMeets
     */
    public function removeAssessedValuationMeet(\FormGeneratorBundle\Entity\ValuationMeet $assessedValuationMeets)
    {
        $this->assessedValuationMeets->removeElement($assessedValuationMeets);
    }

    /**
     * Get assessedValuationMeets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAssessedValuationMeets()
    {
        return $this->assessedValuationMeets;
    }

    /**
     * Add assessorProfessionalMeets
     *
     * @param \FormGeneratorBundle\Entity\ProfessionalMeet $assessorProfessionalMeets
     * @return User
     */
    public function addAssessorProfessionalMeet(\FormGeneratorBundle\Entity\ProfessionalMeet $assessorProfessionalMeets)
    {
        $this->assessorProfessionalMeets[] = $assessorProfessionalMeets;
        $assessorProfessionalMeets->setAssessor($this);

        return $this;
    }

    /**
     * Remove assessorProfessionalMeets
     *
     * @param \FormGeneratorBundle\Entity\ProfessionalMeet $assessorProfessionalMeets
     */
    public function removeAssessorProfessionalMeet(\FormGeneratorBundle\Entity\ProfessionalMeet $assessorProfessionalMeets)
    {
        $this->assessorProfessionalMeets->removeElement($assessorProfessionalMeets);
    }

    /**
     * Get assessorProfessionalMeets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAssessorProfessionalMeets()
    {
        return $this->assessorProfessionalMeets;
    }

    /**
     * Add assessedProfessionalMeets
     *
     * @param \FormGeneratorBundle\Entity\ProfessionalMeet $assessedProfessionalMeets
     * @return User
     */
    public function addAssessedProfessionalMeet(\FormGeneratorBundle\Entity\ProfessionalMeet $assessedProfessionalMeets)
    {
        $this->assessedProfessionalMeets[] = $assessedProfessionalMeets;
        $assessedProfessionalMeets->setAssessed($this);

        return $this;
    }

    /**
     * Remove assessedProfessionalMeets
     *
     * @param \FormGeneratorBundle\Entity\ProfessionalMeet $assessedProfessionalMeets
     */
    public function removeAssessedProfessionalMeet(\FormGeneratorBundle\Entity\ProfessionalMeet $assessedProfessionalMeets)
    {
        $this->assessedProfessionalMeets->removeElement($assessedProfessionalMeets);
    }

    /**
     * Get assessedProfessionalMeets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAssessedProfessionalMeets()
    {
        return $this->assessedProfessionalMeets;
    }
}
