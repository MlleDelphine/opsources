<?php

namespace FormGeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProfessionalCollectionAttribute
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FormGeneratorBundle\Entity\Repository\ProfessionalCollectionAttributeRepository")
 */
class ProfessionalCollectionAttribute
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
     * @var string : contiendra la conf de TOUS les champs de la collection
     *
     * @ORM\Column(name="fieldType", type="string", length=255)
     */
    private $fieldType;

    /**
     * @var string : contiendra un tableau des valeurs (hope so)
     *
     * @ORM\Column(name="value", type="text")
     */
    private $value;
//
//    /**
//     * @ORM\ManyToOne(targetEntity="FormGeneratorBundle\Entity\ProfessionalMeet", inversedBy="collectionAttributes")
//     * @ORM\JoinColumn(name="professionalmeet_id", referencedColumnName="id")
//     */
//    private $professionalMeet;

    /**
     * @ORM\ManyToOne(targetEntity="FormGeneratorBundle\Entity\ProfessionalAttribute", inversedBy="collectionAttributes")
     * @ORM\JoinColumn(name="professionalattribute_id", referencedColumnName="id")
     */
    private $professionalAttribute;


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
     * @return ProfessionalCollectionAttribute
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
     * Set fieldType
     *
     * @param string $fieldType
     * @return ProfessionalCollectionAttribute
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
     * Set value
     *
     * @param string $value
     * @return ProfessionalCollectionAttribute
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

//    /**
//     * Set professionalMeet
//     *
//     * @param \FormGeneratorBundle\Entity\ProfessionalMeet $professionalMeet
//     * @return ProfessionalAttribute
//     */
//    public function setProfessionalMeet(\FormGeneratorBundle\Entity\ProfessionalMeet $professionalMeet = null)
//    {
//        $this->professionalMeet = $professionalMeet;
//
//        return $this;
//    }
//
//    /**
//     * Get professionalMeet
//     *
//     * @return \FormGeneratorBundle\Entity\ProfessionalMeet
//     */
//    public function getProfessionalMeet()
//    {
//        return $this->professionalMeet;
//    }

    /**
     * Set professionalMeet
     *
     * @param \FormGeneratorBundle\Entity\ProfessionalAttribute $professionalAttribute
     * @return ProfessionalAttribute
     */
    public function setProfessionalAttribute(\FormGeneratorBundle\Entity\ProfessionalAttribute $professionalAttribute = null)
    {
        $this->professionalAttribute = $professionalAttribute;

        return $this;
    }

    /**
     * Get professionalMeet
     *
     * @return \FormGeneratorBundle\Entity\ProfessionalAttribute
     */
    public function getProfessionalAttribute()
    {
        return $this->professionalAttribute;
    }

}
