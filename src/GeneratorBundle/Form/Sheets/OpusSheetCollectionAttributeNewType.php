<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 10/06/2015
 * Time: 17:19
 */

namespace GeneratorBundle\Form\Sheets;

use GeneratorBundle\Form\Sheets\OpusSheetAttributeNewType;
use GeneratorBundle\Form\Type\CustomCollectionAttributeType;
use GeneratorBundle\Form\Type\CustomCollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver ;
use Symfony\Component\Form\FormEvents;

use GeneratorBundle\Form\Type\CustomRadioType;


class OpusSheetCollectionAttributeNewType extends AbstractType{

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
            function (\Symfony\Component\Form\FormEvent $event) {
                if (null != $event->getData()) {
                    $form = $event->getForm();
                    $data = $event->getData();
                    $options = array();
                    //collections : dans yml
                    foreach ($this->attributes as $key => $allConf) {
                        $form->add(
                            'attributes',
                            new CustomCollectionType(count($allConf['child'])),
                            array(
                                'type' => new OpusSheetAttributeNewType($allConf['child'], $allConf['conf']['attr']['data-tab']),
                                'allow_add' => true,
                                'allow_delete' => true,
                                'by_reference' => false,
                                'required' => false,
                                'label' => false,
                                'attr' => array('data-tab' => $this->tab)
                            )
                        );
                    }
                    $form->add('type', 'hidden', array('label' => false, 'attr' => array('data-tab' => $this->tab)));
                }

            });
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GeneratorBundle\Entity\OpusCollection'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'generator_sheet_collectionattribute';
    }
}