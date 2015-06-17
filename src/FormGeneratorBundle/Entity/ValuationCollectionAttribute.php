<?php

namespace FormGeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ValuationCollectionAttribute
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FormGeneratorBundle\Entity\Repository\ValuationCollectionAttributeRepository")
 */
class ValuationCollectionAttribute
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
//     * @ORM\ManyToOne(targetEntity="FormGeneratorBundle\Entity\ValuationMeet", inversedBy="collectionAttributes")
//     * @ORM\JoinColumn(name="valuationmeet_id", referencedColumnName="id")
//     */
//    private $valuationMeet;

    /**
     * @ORM\ManyToOne(targetEntity="FormGeneratorBundle\Entity\ValuationAttribute", inversedBy="collectionAttributes")
     * @ORM\JoinColumn(name="valuationattribute_id", referencedColumnName="id")
     */
    private $valuationAttribute;


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
     * @return ValuationCollectionAttribute
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
     * @return ValuationCollectionAttribute
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
     * @return ValuationCollectionAttribute
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
//     * Set valuationMeet
//     *
//     * @param \FormGeneratorBundle\Entity\ValuationMeet $valuationMeet
//     * @return ValuationAttribute
//     */
//    public function setValuationMeet(\FormGeneratorBundle\Entity\ValuationMeet $valuationMeet = null)
//    {
//        $this->valuationMeet = $valuationMeet;
//
//        return $this;
//    }
//
//    /**
//     * Get valuationMeet
//     *
//     * @return \FormGeneratorBundle\Entity\ValuationMeet
//     */
//    public function getValuationMeet()
//    {
//        return $this->valuationMeet;
//    }

    /**
     * Set valuationMeet
     *
     * @param \FormGeneratorBundle\Entity\ValuationAttribute $valuationAttribute
     * @return ValuationAttribute
     */
    public function setValuationAttribute(\FormGeneratorBundle\Entity\ValuationAttribute $valuationAttribute = null)
    {
        $this->valuationAttribute = $valuationAttribute;

        return $this;
    }

    /**
     * Get valuationMeet
     *
     * @return \FormGeneratorBundle\Entity\ValuationAttribute
     */
    public function getValuationAttribute()
    {
        return $this->valuationAttribute;
    }

}
