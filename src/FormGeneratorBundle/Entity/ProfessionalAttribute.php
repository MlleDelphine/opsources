<?php

namespace FormGeneratorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProfessionalAttribute
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FormGeneratorBundle\Entity\Repository\ProfessionalAttributeRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ProfessionalAttribute
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
     * @ORM\Column(name="fieldType", type="string", length=255)
     */
    private $fieldType;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text")
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="FormGeneratorBundle\Entity\ProfessionalMeet", inversedBy="attributes")
     * @ORM\JoinColumn(name="professionalmeet_id", referencedColumnName="id")
     */
    private $professionalMeet;

    /**
     * @ORM\OneToMany(targetEntity="FormGeneratorBundle\Entity\ProfessionalCollectionAttribute", mappedBy="professionalAttribute", cascade={"remove", "persist"})
     */
    private $collectionAttributes;


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
     * @return ProfessionalAttribute
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
     * Set value
     *
     * @param string $value
     * @return ProfessionalAttribute
     */
    public function setValue($value)
    {
        /**
         * On ne stocke que les ID pour alléger la BdD
         */
        if($value && $this->fieldType == 'entity' ){
            //Si on a stocké une collection d'objet (multiple true généralement)
            if ($value instanceof ArrayCollection) {
                $objID = array();
                foreach ($value as $obj) {
                    $objID[] = $obj->getId();
                }
                $this->value = serialize($objID);

            } else {
                $this->value = serialize($value->getId());
            }
        }
        else {
            $this->value = serialize($value);
        }

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return unserialize($this->value);
    }

    /**
     * Set professionalMeet
     *
     * @param \FormGeneratorBundle\Entity\ProfessionalMeet $professionalMeet
     * @return ProfessionalAttribute
     */
    public function setProfessionalMeet(\FormGeneratorBundle\Entity\ProfessionalMeet $professionalMeet = null)
    {
        $this->professionalMeet = $professionalMeet;

        return $this;
    }

    /**
     * Get professionalMeet
     *
     * @return \FormGeneratorBundle\Entity\ProfessionalMeet
     */
    public function getProfessionalMeet()
    {
        return $this->professionalMeet;
    }

    /**
     * Set fieldType
     *
     * @param string $fieldType
     * @return ProfessionalAttribute
     */
    public function setFieldType($fieldType)
    {
        $this->fieldType = $fieldType;

        return $this;
    }

    /**
     * Get fieldType
     *
     * @return string
     */
    public function getFieldType()
    {
        return $this->fieldType;
    }

    /**
     * Add collectionAttributes
     *
     * @param \FormGeneratorBundle\Entity\ProfessionalCollectionAttribute $collectionAttributes
     * @return ProfessionalAttribute
     */
    public function addCollectionAttribute(\FormGeneratorBundle\Entity\ProfessionalCollectionAttribute $collectionAttributes)
    {
        $collectionAttributes->setProfessionalAttribute($this);
        $this->collectionAttributes[] = $collectionAttributes;

        return $this;
    }

    /**
     * Remove collectionAttributes
     *
     * @param \FormGeneratorBundle\Entity\ProfessionalCollectionAttribute $collectionAttributes
     */
    public function removeCollectionAttribute(\FormGeneratorBundle\Entity\ProfessionalCollectionAttribute $collectionAttributes)
    {
        $this->collectionAttributes->removeElement($collectionAttributes);
    }

    /**
     * Get collectionAttributes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCollectionAttributes()
    {
        return $this->collectionAttributes;
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->collectionAttributes = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
