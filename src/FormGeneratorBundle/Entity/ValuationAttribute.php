<?php

namespace FormGeneratorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ValuationAttribute
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FormGeneratorBundle\Entity\Repository\ValuationAttributeRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ValuationAttribute
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
     * @ORM\ManyToOne(targetEntity="FormGeneratorBundle\Entity\ValuationMeet", inversedBy="attributes")
     * @ORM\JoinColumn(name="valuationmeet_id", referencedColumnName="id")
     */
    private $valuationMeet;

    /**
     * @ORM\OneToMany(targetEntity="FormGeneratorBundle\Entity\ValuationCollectionAttribute", mappedBy="valuationAttribute", cascade={"remove", "persist"})
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
     * @return ValuationAttribute
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
     * @return ValuationAttribute
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
     * Set valuationMeet
     *
     * @param \FormGeneratorBundle\Entity\ValuationMeet $valuationMeet
     * @return ValuationAttribute
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

    /**
     * Set fieldType
     *
     * @param string $fieldType
     * @return ValuationAttribute
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
     * @param \FormGeneratorBundle\Entity\ValuationCollectionAttribute $collectionAttributes
     * @return ValuationAttribute
     */
    public function addCollectionAttribute(\FormGeneratorBundle\Entity\ValuationCollectionAttribute $collectionAttributes)
    {
        $collectionAttributes->setValuationAttribute($this);
        $this->collectionAttributes[] = $collectionAttributes;

        return $this;
    }

    /**
     * Remove collectionAttributes
     *
     * @param \FormGeneratorBundle\Entity\ValuationCollectionAttribute $collectionAttributes
     */
    public function removeCollectionAttribute(\FormGeneratorBundle\Entity\ValuationCollectionAttribute $collectionAttributes)
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



}
