<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 12/06/2015
 * Time: 10:49
 */

namespace AppBundle\Service;

use AppBundle\Entity\ValuationMeet;
use AppBundle\Entity\ValuationAttribute;
use AppBundle\Entity\ValuationCollectionAttribute;
use AppBundle\Form\ValuationMeetType;
use Symfony\Component\Form\FormFactory;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\Form;


class PrePopulateEntity{

    private $formFactory;
    private $router;
    private $em;

    public function __construct(FormFactory $formFactory, Router $router, EntityManager $em) {
        $this->formFactory = $formFactory;
        $this->router      = $router;
        $this->em          = $em;
    }

    /**
     * Prédéfinit l'entité principale avec ses attributs/collection
     * @param $entity
     * @param $attributes
     * @return mixed
     */

    public function populateValuationMeet($entity, $attributes){

        foreach($attributes['attr'] as $allConf){
            $attr = new ValuationAttribute();
            $attr->setName($allConf['id']);
            $attr->setFieldType($allConf['type']);
            $attr->setValue(null);
            $entity->addAttribute($attr);
            if($allConf['type'] == 'collection'){
                //On crée les CollectionAttributes
                $number = $allConf['number'];
                for($i = 1; $i <= $number; $i ++){
                    foreach ($allConf['child'] as $childConf) {
                        $collAttr = new ValuationCollectionAttribute();
                        $collAttr->setName($childConf['id']);
                        $collAttr->setFieldType($childConf['type']);
                        $collAttr->setValue(null);

                        $attr->addCollectionAttribute($collAttr);

                    }
                }

            }
        }

        return $this->createValuationMeetCreateForm($entity, $attributes);

    }

    /**
     * Prédéfinit l'entité principal avec ses attributs/collection
     * @param $entity
     * @param $attributes
     * @return mixed
     */

    public function populateValuationMeetForEdit($entity, $attributes){

        $valuationAttr = $entity->getAttributes();

        $attrInConf = $this->associateKeyId($attributes['attr']);

        $attrInEntity = $this->existingNameField($valuationAttr);

        $attrToAdd = array();


        //Si le champ dans la conf n'est pas dans l'entité : on l'ajoute parmi les attributes à ajouter
        foreach ($attrInConf as $k => $attrConf) {

            if(!in_array($attrConf, $attrInEntity)){
                $attrToAdd[] = $attributes['attr'][$k];
            }
        }


        foreach($attrToAdd as $allConf){
            $attr = new ValuationAttribute();
            $attr->setName($allConf['id']);
            $attr->setFieldType($allConf['type']);
            $attr->setValue(null);
            $entity->addAttribute($attr);
            if($allConf['type'] == 'collection'){
                //On crée les CollectionAttributes
                $number = $allConf['number'];
                for($i = 1; $i <= $number; $i ++){
                    foreach ($allConf['child'] as $childConf) {
                        $collAttr = new ValuationCollectionAttribute();
                        $collAttr->setName($childConf['id']);
                        $collAttr->setFieldType($childConf['type']);
                        $collAttr->setValue(null);

                        $attr->addCollectionAttribute($collAttr);

                    }
                }

            }
        }

        return $this->createValuationMeetEditForm($entity, $attributes);

    }

    /**
     * Retourne le formulaire de création construit de ValuationMeet avec ses attributs/collections
     * @param $entity
     * @param $attributes
     * @return mixed
     */

    private function createValuationMeetCreateForm(ValuationMeet $entity, $attributes){

        $form = $this->formFactory->create(
            new ValuationMeetType($attributes, $this->em),
            $entity,
            array(
                'action' => $this->router->generate('create_valuationmeet'),
                'method' => 'POST'
            )
        );
        $form->add('submit', 'submit', array('label' => 'Créer', 'attr' => array('class' => 'btn btn-sm btn-primary')));

        return $form;
    }

    /**
     * Retourne le formulaire d'édition construit de ValuationMeet avec ses attributs/collections
     * @param $entity
     * @param $attributes
     * @return mixed
     */
    public function createValuationMeetEditForm(ValuationMeet $entity, $attributes)
    {

        $form = $this->formFactory->create(new ValuationMeetType($attributes, $this->em), $entity, array(
            'action' => $this->router->generate('update_valuationmeet', array('id' => $entity->getId())),
            'method' => 'PUT'));

        $form->add('submit', 'submit', array('label' => 'Modifier', 'attr' => array('class' => 'btn btn-sm btn-primary')));

        return $form;
    }

    /**
     * Associe la clé parent à l'id du champ du fichier de conf .yml
     * @param $array
     * @return array
     */
    private function associateKeyId($array){
        $result = array();
        foreach ($array as $k => $v) {
            $result[$k] = $v['id'];
        }

        return $result;
    }

    /**
     * Associe la clé parent à l'id du champ du fichier de conf .yml
     * @param $array
     * @return array*
     */
    private function existingNameField($attr){
        $result = array();
        foreach ($attr as $k => $v) {
            $result[$k] = $v->getName();
        }

        return $result;
    }
}