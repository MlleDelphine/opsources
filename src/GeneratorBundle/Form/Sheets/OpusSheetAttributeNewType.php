<?php

/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 03/06/2015
 * Time: 12:08.
 */

namespace GeneratorBundle\Form\Sheets;

use GeneratorBundle\Form\Type\CustomRadioType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;

class OpusSheetAttributeNewType extends AbstractType
{
    protected $attributes;
    protected $tab;

    public function __construct($attributes, $access = null)
    {
        $this->attributes = $attributes;
        $this->access = $access;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formFactory = $builder->getFormFactory();

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (\Symfony\Component\Form\FormEvent $event) use ($formFactory, $builder) {
                if (null != $event->getData()) {
                    $form = $event->getForm();
                    $data = $event->getData();
                    $options = array();
                    $fieldName = 'value';
                    foreach ($this->attributes as $allConf) {

                        if ($allConf['id'] == $data->getLabel()) {
                            //Si on a un champ date  /time on stockera dans valueDate sinon dans value
                            if ($allConf['type'] == 'date' || $allConf['type'] == 'datetime' || $allConf['type'] == 'time' || $allConf['type'] == 'birthday' || $allConf['type'] == 'genemu_jquerydate') {
                                $fieldName = 'valueDate';
                            } elseif ($allConf['type'] == 'file') {
                                $fieldName = 'valueData';
                            }

                            if ($allConf['type'] == 'choice') {
                                $allConf['type'] = new CustomRadioType();
                            }
                            foreach ($allConf['conf'] as $name => $value) {
                                $options[$name] = $value;
                            }

                            //Seul le manager peut remplir certains champs
                            if($this->access == "none" || $this->access == "drh_decision"){
                                $options['attr']['readonly'] = true;
                            }
                            elseif($this->access == "evaluator_write"){
                                if (isset($allConf['conf']['attr']['data-access']) && $allConf['conf']['attr']['data-access'] == 'evaluate') {
                                    // $options['disabled'] = true;
                                    $options['attr']['readonly'] = true;
                                }
                            }
                            elseif($this->access == "evaluate_write"){
                                $options['attr']['readonly'] = true;

                                if (isset($allConf['conf']['attr']['data-access']) && $allConf['conf']['attr']['data-access'] == 'evaluate') {
                                    unset($options['attr']['readonly']);
                                }
                            }

                            $form->add(
                                $fieldName,
                                $allConf['type'],
                                $options
                            );

                        }
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
            'data_class' => 'GeneratorBundle\Entity\OpusAttribute',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'generator_sheet_attribute';
    }
}
