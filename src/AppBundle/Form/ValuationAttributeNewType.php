<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 03/06/2015
 * Time: 12:08
 */
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver ;
use Symfony\Component\Form\FormEvents;

class ValuationAttributeNewType extends AbstractType{

    protected $attributes;

    public function __construct ($attributes)
    {
        $this->attributes = $attributes;

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
                                }
                                if(!$confChild) {
                                    $form->add(
                                        $fieldName,
                                        $allConf['type'],
                                        $options
                                    );
                                }else{
                                    $form->add(
                                        'collectionAttributes', 'collection', array(
                                        'type' => new ValuationCollectionAttributeNewType($confChild),
                                        'allow_add' => true,
                                        'allow_delete' => true,
                                        'by_reference' => false,
                                        'required' => false,
                                        'label' => $label));
                                }

                            }
                        }
                        $form->add('name', 'hidden');
                        $form->add('fieldType', 'hidden');
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
            'data_class' => 'AppBundle\Entity\ValuationAttribute'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'formgenerator_valuation_attribute';
    }
}