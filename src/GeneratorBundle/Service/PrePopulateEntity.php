<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 12/06/2015
 * Time: 10:49
 */

namespace GeneratorBundle\Service;

use FormGeneratorBundle\Entity\ProfessionalMeet;
use FormGeneratorBundle\Entity\ProfessionalAttribute;
use FormGeneratorBundle\Entity\ProfessionalCollectionAttribute;
use FormGeneratorBundle\Entity\WorkCondition;
use FormGeneratorBundle\Form\Professional\ProfessionalMeetType;

use FormGeneratorBundle\Entity\ValuationMeet;
use FormGeneratorBundle\Entity\Skill;
use FormGeneratorBundle\Entity\ValuationAttribute;
use FormGeneratorBundle\Entity\ValuationCollectionAttribute;
use FormGeneratorBundle\Form\Valuation\ValuationMeetType;

use FormGeneratorBundle\Entity\ConditionsMeet;
use FormGeneratorBundle\Entity\ConditionsAttribute;
use FormGeneratorBundle\Entity\ConditionsCollectionAttribute;
use FormGeneratorBundle\Form\Conditions\ConditionsMeetType;

use GeneratorBundle\Entity\OpusAttribute;
use GeneratorBundle\Entity\OpusCollection;
use GeneratorBundle\Entity\OpusSheet;

use GeneratorBundle\Form\Sheets\OpusSheetType;
use Symfony\Component\Form\FormFactory;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\Form;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;


class PrePopulateEntity{

    private $formFactory;
    private $router;
    private $em;
    private $security;

    public function __construct(FormFactory $formFactory, Router $router, EntityManager $em, TokenStorage $security) {
        $this->formFactory = $formFactory;
        $this->router      = $router;
        $this->em          = $em;
        $this->security = $security;
    }

    /**
     * ValuationMeet
     *
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

        $capacities = $this->em->getRepository('FormGeneratorBundle:Capacity')->findAll();
        foreach ($capacities as $capacity) {
            $skill = new Skill();
            $skill->setName($capacity->getName());
            $entity->addSkill($skill);
        }


        return $this->createValuationMeetCreateForm($entity, $attributes);

    }

    public function populateOpusSheet(OpusSheet $sheet, $attributes){

        foreach($attributes['attr'] as $allConf){
            $attr = new OpusAttribute();
            $attr->setLabel($allConf['id']);
            $sheet->addAttribute($attr);
        }

        if(array_key_exists('collections', $attributes)) {
            foreach ($attributes['collections'] as $collection) {
                $number = 5;
                if(array_key_exists('number', $collection)){
                    $number = $collection['number'];
                }

                for($i = 0; $i < $number; $i ++){
                    $opusCollection = new OpusCollection();
                    $opusCollection->setType($collection['id']);
                    $opusCollection->setLocation($i+1);

                    $sheet->addCollection($opusCollection);

                    foreach ($collection['child'] as $child) {
                        $opusAttribute = new OpusAttribute();
                        $opusAttribute->setLabel($child['id']);

                        if (array_key_exists("predefined_values", $collection) && array_key_exists($child['id'], $collection['predefined_values'])) {
                            $opusAttribute->setValue($collection['predefined_values'][$child['id']][$i]);
                        }
                        $opusCollection->addAttribute($opusAttribute);

                    }
                }
            }
        }

        return $this->createOpusSheetCreateForm($sheet, $attributes);

    }

    /**
     * ProfessionalMeet
     *
     * Prédéfinit l'entité principale avec ses attributs/collection
     * @param $entity
     * @param $attributes
     * @return mixed
     */

    public function populateProfessionalMeet($entity, $attributes){

        foreach($attributes['attr'] as $allConf){
            $attr = new ProfessionalAttribute();
            $attr->setName($allConf['id']);
            $attr->setFieldType($allConf['type']);
            $attr->setValue(null);
            $entity->addAttribute($attr);
            if($allConf['type'] == 'collection'){
                //On crée les CollectionAttributes
                $number = $allConf['number'];
                for($i = 1; $i <= $number; $i ++){
                    foreach ($allConf['child'] as $childConf) {
                        $collAttr = new ProfessionalCollectionAttribute();
                        $collAttr->setName($childConf['id']);
                        $collAttr->setFieldType($childConf['type']);
                        $collAttr->setValue(null);

                        $attr->addCollectionAttribute($collAttr);

                    }
                }

            }
        }

        return $this->createProfessionalMeetCreateForm($entity, $attributes);

    }

    /**
     * ConditionsMeet
     *
     * Prédéfinit l'entité principale avec ses attributs/collection
     * @param $entity
     * @param $attributes
     * @return mixed
     */

    public function populateConditionsMeet($entity, $attributes){

        foreach($attributes['attr'] as $allConf){
            $attr = new ConditionsAttribute();
            $attr->setName($allConf['id']);
            $attr->setFieldType($allConf['type']);
            $attr->setValue(null);
            $entity->addAttribute($attr);
            if($allConf['type'] == 'collection'){
                //On crée les CollectionAttributes
                $number = $allConf['number'];
                for($i = 1; $i <= $number; $i ++){
                    foreach ($allConf['child'] as $childConf) {
                        $collAttr = new ConditionsCollectionAttribute();
                        $collAttr->setName($childConf['id']);
                        $collAttr->setFieldType($childConf['type']);
                        $collAttr->setValue(null);

                        $attr->addCollectionAttribute($collAttr);

                    }
                }

            }
        }

        $conditions = $this->em->getRepository('FormGeneratorBundle:Condition')->findAll();
        foreach ($conditions as $condition) {
            $workCondition = new WorkCondition();
            $workCondition->setName($condition->getName());
            $entity->addWorkCondition($workCondition);
        }

        return $this->createConditionsMeetCreateForm($entity, $attributes);

    }

    /**
     * ValuationMeet
     * Prédéfinit l'entité principale avec ses attributs/collection
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
     * ProfessionalMeet
     *
     * Prédéfinit l'entité principale avec ses attributs/collection
     * @param $entity
     * @param $attributes
     * @return mixed
     */

    public function populateProfessionalMeetForEdit($entity, $attributes){

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
            $attr = new ProfessionalAttribute();
            $attr->setName($allConf['id']);
            $attr->setFieldType($allConf['type']);
            $attr->setValue(null);
            $entity->addAttribute($attr);
            if($allConf['type'] == 'collection'){
                //On crée les CollectionAttributes
                $number = $allConf['number'];
                for($i = 1; $i <= $number; $i ++){
                    foreach ($allConf['child'] as $childConf) {
                        $collAttr = new ProfessionalCollectionAttribute();
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
     * ConditionsMeet
     *
     * Prédéfinit l'entité principale avec ses attributs/collection
     * @param $entity
     * @param $attributes
     * @return mixed
     */

    public function populateConditionsMeetForEdit($entity, $attributes){

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
            $attr = new ConditionsAttribute();
            $attr->setName($allConf['id']);
            $attr->setFieldType($allConf['type']);
            $attr->setValue(null);
            $entity->addAttribute($attr);
            if($allConf['type'] == 'collection'){
                //On crée les CollectionAttributes
                $number = $allConf['number'];
                for($i = 1; $i <= $number; $i ++){
                    foreach ($allConf['child'] as $childConf) {
                        $collAttr = new ConditionsCollectionAttribute();
                        $collAttr->setName($childConf['id']);
                        $collAttr->setFieldType($childConf['type']);
                        $collAttr->setValue(null);

                        $attr->addCollectionAttribute($collAttr);

                    }
                }

            }
        }

        return $this->createConditionsMeetEditForm($entity, $attributes);

    }

    /**
     * ValuationMeet
     *
     * Retourne le formulaire de création construit de ValuationMeet avec ses attributs/collections
     * @param $entity
     * @param $attributes
     * @return mixed
     */

    private function createValuationMeetCreateForm(ValuationMeet $entity, $attributes){

        $form = $this->formFactory->create(
            new ValuationMeetType($attributes, $this->em, $this->security),
            $entity,
            array(
                'action' => $this->router->generate('create_valuationmeet'),
                'method' => 'POST'
            )
        );
        $form->add('save', 'submit', array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-lg btn-info')));
        $form->add('submit', 'submit', array('label' => 'Valider', 'attr' => array('class' => 'btn btn-lg btn-success')));

        return $form;
    }

    /**
     * OpusSheet création du formulaire de création
     *
     * @param OpusSheet $entity
     * @param $attributes
     * @return Form|\Symfony\Component\Form\FormInterface
     */

    private function createOpusSheetCreateForm(OpusSheet $entity, $attributes){

        $form = $this->formFactory->create(
            new OpusSheetType($attributes, $this->em, $this->security),
            $entity,
            array(
                'action' => $this->router->generate('generator_homepage', array('codeText' => "trulutut")),
                'method' => 'POST'
            )
        );
        $form->add('save', 'submit', array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-lg btn-info')));
        $form->add('submit', 'submit', array('label' => 'Valider', 'attr' => array('class' => 'btn btn-lg btn-success')));

        return $form;
    }

    /**
     * ProfessionalMeet
     *
     * Retourne le formulaire de création construit de ConditionsMeet avec ses attributs/collections
     * @param $entity
     * @param $attributes
     * @return mixed
     */

    private function createProfessionalMeetCreateForm(ProfessionalMeet $entity, $attributes){

        $form = $this->formFactory->create(
            new ProfessionalMeetType($attributes, $this->em, $this->security),
            $entity,
            array(
                'action' => $this->router->generate('create_professionalmeet'),
                'method' => 'POST'
            )
        );
        $form->add('save', 'submit', array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-lg btn-info')));
        $form->add('submit', 'submit', array('label' => 'Valider', 'attr' => array('class' => 'btn btn-lg btn-success')));

        return $form;
    }

    /**
     * ConditionsMeet
     *
     * Retourne le formulaire de création construit de ConditionsMeet avec ses attributs/collections
     * @param $entity
     * @param $attributes
     * @return mixed
     */

    private function createConditionsMeetCreateForm(ConditionsMeet $entity, $attributes){

        $form = $this->formFactory->create(
            new ConditionsMeetType($attributes, $this->em, $this->security),
            $entity,
            array(
                'action' => $this->router->generate('create_conditionsmeet'),
                'method' => 'POST'
            )
        );
        $form->add('save', 'submit', array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-lg btn-info')));
        $form->add('submit', 'submit', array('label' => 'Valider', 'attr' => array('class' => 'btn btn-lg btn-success')));

        return $form;
    }

    /**
     * ValuationMeet
     *
     * Retourne le formulaire d'édition construit de ValuationMeet avec ses attributs/collections
     * @param $entity
     * @param $attributes
     * @return mixed
     */
    public function createValuationMeetEditForm(ValuationMeet $entity, $attributes)
    {

        $form = $this->formFactory->create(new ValuationMeetType($attributes, $this->em, $this->security), $entity, array(
            'action' => $this->router->generate('update_valuationmeet', array('id' => $entity->getId())),
            'method' => 'PUT'));

        $form->add('refused', 'submit', array('label' => 'Invalider', 'attr' => array('class' => 'btn btn-lg btn-danger')));
        $form->add('submit', 'submit', array('label' => 'Valider', 'attr' => array('class' => 'btn btn-lg btn-success')));

        return $form;
    }

    /**
     * ProfessionalMeet
     *
     * Retourne le formulaire d'édition construit de ValuationMeet avec ses attributs/collections
     * @param $entity
     * @param $attributes
     * @return mixed
     */
    public function createProfessionalMeetEditForm(ProfessionalMeet $entity, $attributes)
    {

        $form = $this->formFactory->create(new ProfessionalMeetType($attributes, $this->em, $this->security), $entity, array(
            'action' => $this->router->generate('update_professionalnmeet', array('id' => $entity->getId())),
            'method' => 'PUT'));

        $form->add('refused', 'submit', array('label' => 'Invalider', 'attr' => array('class' => 'btn btn-lg btn-danger')));
        $form->add('submit', 'submit', array('label' => 'Valider', 'attr' => array('class' => 'btn btn-lg btn-success')));

        return $form;
    }

    /**
     * ConditionsMeet
     *
     * Retourne le formulaire d'édition construit de ConditionsMeet avec ses attributs/collections
     * @param $entity
     * @param $attributes
     * @return mixed
     */
    public function createConditionsMeetEditForm(ConditionsMeet $entity, $attributes)
    {

        $form = $this->formFactory->create(new ConditionsMeetType($attributes, $this->em, $this->security), $entity, array(
            'action' => $this->router->generate('update_conditionsmeet', array('id' => $entity->getId())),
            'method' => 'PUT'));

        $statusPending = $this->em->getRepository('FormGeneratorBundle:Status')->findOneBy(array('code' => 'pending_m'));
        if($entity->getStatus() === $statusPending){
            //Si le manager l'a créé puis seulement enregistré il peut enregistrer à nouveau ou valider
            $form->add('save', 'submit', array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-lg btn-info')));
        }
        else{
            //Si le manager a eu le retour de l'utilisateur il peut valider ou invalider
            $form->add('refused', 'submit', array('label' => 'Invalider', 'attr' => array('class' => 'btn btn-lg btn-danger')));
        }
        $form->add('submit', 'submit', array('label' => 'Valider', 'attr' => array('class' => 'btn btn-lg btn-success')));

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