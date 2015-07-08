<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 03/06/2015
 * Time: 12:08
 */
namespace FormGeneratorBundle\Form\Conditions;

use FormGeneratorBundle\Form\Type\CustomCollectionAttributeType;
use FormGeneratorBundle\Form\Type\CustomRadioType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver ;
use Symfony\Component\Form\FormEvents;
use FormGeneratorBundle\Form\Type\CustomCollectionType;

class ConditionsAttributeNewType extends AbstractType{

    protected $attributes;
    protected $tab;

    public function __construct ($attributes, $tab = null)
    {
        $this->attributes = $attributes;
        $this->tab = $tab;

    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formFactory = $builder->getFormFactory();

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (\Symfony\Component\Form\FormEvent $event) use ($formFactory) {
                if (null != $event->getData()) {
                    $valAttributeEntity = $event->getData();
                    if (!$event || null === $valAttributeEntity->getId()) {
                        $form = $event->getForm();
                        $data = $event->getData();
                        $options = array();
                        $confChild = false;
                        foreach ($this->attributes as $allConf) {
                            if ($allConf['id'] == $data->getName()) {
                                if($allConf['type'] == 'choice'){
                                    $allConf['type'] = new CustomRadioType();
                                }
                                foreach ($allConf['conf'] as $name => $value) {
                                    //Donc champ collection, spécifique, à créer en dehors
                                    if($name == 'type'){
                                        $confChild = $allConf['child'];
                                        $label = $allConf['conf']['label'];
                                    }

                                    else{
                                        $options[$name] = $value;
                                        $fieldName = 'value';
                                    }
                                    $this->tab = $allConf['conf']['attr']['data-tab'];
                                }
                                if(!$confChild) {
                                    $form->add(
                                        $fieldName,
                                        $allConf['type'],
                                        $options
                                    );
                                }else{
                                    $form->add(
                                        'collectionAttributes',  new CustomCollectionType(count($confChild)), array(
                                        'type' => new ConditionsCollectionAttributeNewType($confChild),
                                        'allow_add' => true,
                                        'allow_delete' => true,
                                        'by_reference' => false,
                                        'required' => false,
                                        'label' => $label));
                                }
                            }
                        }
                        $form->add('name', 'hidden', array('label' => false, 'attr' => array('data-tab' => $this->tab)));
                        $form->add('fieldType', 'hidden', array('label' => false, 'attr' => array('data-tab' => $this->tab)));
                    }
                }
            });
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FormGeneratorBundle\Entity\ConditionsAttribute'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'formgenerator_conditions_attribute';
    }
}