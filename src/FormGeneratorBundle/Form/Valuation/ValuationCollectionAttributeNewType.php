<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 10/06/2015
 * Time: 17:19
 */

namespace FormGeneratorBundle\Form\Valuation;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver ;
use Symfony\Component\Form\FormEvents;

use FormGeneratorBundle\Form\Type\CustomRadioType;


class ValuationCollectionAttributeNewType extends AbstractType{

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
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (\Symfony\Component\Form\FormEvent $event)  {
                if (null != $event->getData()) {
                    $form = $event->getForm();
                    $data = $event->getData();
                    $options = array();
                    foreach ($this->attributes as $allConf) {
                        if($allConf['type'] == 'choice'){
                            $allConf['type'] = new CustomRadioType();
                        }
                        if ($allConf['id'] == $data->getName()) {
                            foreach ($allConf['conf'] as $name => $value) {
                                $options[$name] = $value;
                            }
                            $form->add(
                                'value',
                                $allConf['type'],
                                $options
                            );
                        }
                        $this->tab = $allConf['conf']['attr']['data-tab'];
                    }
                    $form->add('name', 'hidden', array('label' => false, 'attr' => array('data-tab' => $this->tab)));
                    $form->add('fieldType', 'hidden', array('label' => false, 'attr' => array('data-tab' => $this->tab)));
                }
            });
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FormGeneratorBundle\Entity\ValuationCollectionAttribute'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'formgenerator_valuation_collectionattribute';
    }
}