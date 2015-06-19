<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 19/06/2015
 * Time: 16:44
 */

namespace FormGeneratorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CustomCollectionAttributeType extends AbstractType{

    private $numberFields;

    public function __construct($numberFields = null){

        $this->numberFields = $numberFields;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['numberFields'] = $this->numberFields;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
        ));
        //   $resolver->setNormalizer(array('attr'=>$href));
    }

    public function getParent()
    {
        return 'collection';
    }

    public function getName()
    {
        return 'customCollectionAttribute';
    }

}