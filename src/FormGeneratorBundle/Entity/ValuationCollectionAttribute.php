<?php

namespace FormGeneratorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ValuationCollectionAttribute
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FormGeneratorBundle\Entity\Repository\ValuationCollectionAttributeRepository")
 * @ORM\HasLifecycleCallbacks
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

    /**
     * @var string
     *
     * @ORM\Column(name="valueText", type="text", nullable=true)
     */
    private $valueText;

    /**
     * @var string
     *
     * @ORM\Column(name="valueDatetime", type="datetime", length=255, nullable=true)
     */
    private $valueDatetime;

    /**
     * @var string
     *
     * @ORM\Column(name="valueDate", type="datetime", length=255, nullable=true)
     */
    private $valueDate;

    /**
     * @var string
     *
     * @ORM\Column(name="valueBool", type="boolean", nullable=true)
     */
    private $valueBool;

    /**
     * @var string
     *
     * @ORM\Column(name="valueString", type="string", length=255, nullable=true)
     */
    private $valueString;

    /**
     * @var integer
     *
     * @ORM\Column(name="valueInteger", type="integer", length=255, nullable=true)
     */
    private $valueInteger;

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


    /**
     * Set valueText
     *
     * @param string $valueText
     * @return ValuationCollectionAttribute
     */
    public function setValueText($valueText)
    {
        $this->valueText = $valueText;

        return $this;
    }

    /**
     * Get valueText
     *
     * @return string 
     */
    public function getValueText()
    {
        return $this->valueText;
    }

    /**
     * Set valueDatetime
     *
     * @param \DateTime $valueDatetime
     * @return ValuationCollectionAttribute
     */
    public function setValueDatetime($valueDatetime)
    {
        $this->valueDatetime = $valueDatetime;

        return $this;
    }

    /**
     * Get valueDatetime
     *
     * @return \DateTime 
     */
    public function getValueDatetime()
    {
        return $this->valueDatetime;
    }

    /**
     * Set valueDate
     *
     * @param \DateTime $valueDate
     * @return ValuationCollectionAttribute
     */
    public function setValueDate($valueDate)
    {
        $this->valueDate = $valueDate;

        return $this;
    }

    /**
     * Get valueDate
     *
     * @return \DateTime 
     */
    public function getValueDate()
    {
        return $this->valueDate;
    }

    /**
     * Set valueBool
     *
     * @param boolean $valueBool
     * @return ValuationCollectionAttribute
     */
    public function setValueBool($valueBool)
    {
        $this->valueBool = $valueBool;

        return $this;
    }

    /**
     * Get valueBool
     *
     * @return boolean 
     */
    public function getValueBool()
    {
        return $this->valueBool;
    }

    /**
     * Set valueString
     *
     * @param string $valueString
     * @return ValuationCollectionAttribute
     */
    public function setValueString($valueString)
    {
        $this->valueString = $valueString;

        return $this;
    }

    /**
     * Get valueString
     *
     * @return string 
     */
    public function getValueString()
    {
        return $this->valueString;
    }

    /**
     * Set valueInteger
     *
     * @param integer $valueInteger
     * @return ValuationCollectionAttribute
     */
    public function setValueInteger($valueInteger)
    {
        $this->valueInteger = $valueInteger;

        return $this;
    }

    /**
     * Get valueInteger
     *
     * @return integer 
     */
    public function getValueInteger()
    {
        return $this->valueInteger;
    }
    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function setValueType(){
        if($this->fieldType == 'date'){
            $this->setValueDate($this->value);
        }
        elseif($this->fieldType == 'datetime' || $this->fieldType == "genemu_jquerydate"){
            $this->setValueDatetime(unserialize($this->value));
        }
        elseif($this->fieldType == 'text'){
            $this->setValueString(unserialize($this->value));
        }
        elseif($this->fieldType == 'textarea' || $this->fieldType == "genemu_tinymce" ){
            $this->setValueText(unserialize($this->value));
        }
        elseif($this->fieldType == 'bool'){
            $this->setValueDate(unserialize($this->value));
        }
        elseif($this->fieldType == 'number'){
            $this->setValueInteger(unserialize($this->value));
        }
        else{
            $this->setValue(unserialize($this->value));
        }
    }

}
