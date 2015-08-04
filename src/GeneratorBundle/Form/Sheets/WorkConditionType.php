<?php

/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 23/06/2015
 * Time: 15:00.
 */

namespace FormGeneratorBundle\Form\Conditions;

use FormGeneratorBundle\Form\Type\CustomRadioType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkConditionType extends AbstractType
{
    private $access = true;

    public function __construct($access)
    {
        $this->access = $access;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($this->access) {
            $builder->add('name', 'textarea', array(
                'label' => 'Critères (conditions)',
                'required' => false,
                'attr' => array(
                    'class' => 'textarea-resize none-sense',
                    'readonly' => true, ), ))
                ->add('evaluation', new CustomRadioType($this->access), array('label' => 'Notation',
                    'choices' => array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 0 => "Pas d'avis"),
                    'required' => true,
                    'expanded' => true,
                    'multiple' => false, ))
                ->add('comments', 'textarea', array('label' => 'Commentaire(s)', 'required' => false, 'attr' => array('class' => 'textarea-resize no-horizontal')));
        } else {
            $builder->add('name', 'textarea', array(
                'label' => 'Critères (conditions)',
                'required' => false,
                'attr' => array(
                    'class' => 'textarea-resize none-sense',
                    'readonly' => true, ), ))
                ->add('evaluation', new CustomRadioType(), array('label' => 'Notation',
                    'choices' => array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 0 => "Pas d'avis"),
                    'required' => true,
                    'expanded' => true,
                    'multiple' => false,
                    'attr' => array('class' => 'disabled'), ))
                ->add('comments', 'textarea', array(
                    'label' => 'Commentaire(s)',
                    'required' => false,
                    'attr' => array('class' => 'textarea-resize no-horizontal', 'readonly' => true), ));
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FormGeneratorBundle\Entity\WorkCondition',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'formgenerator_workCondition';
    }
}
