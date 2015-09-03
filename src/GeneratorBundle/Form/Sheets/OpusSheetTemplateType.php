<?php

/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 29/07/2015
 * Time: 14:30.
 */

namespace GeneratorBundle\Form\Sheets;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class OpusSheetTemplateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$builder->add()
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GeneratorBundle\Entity\OpusSheetTemplate',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'generator_sheet_template';
    }
}
