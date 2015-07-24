<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 22/06/2015
 * Time: 11:31
 */

namespace GeneratorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CustomRadioType extends AbstractType {

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'customRadio';
    }

}