<?php

namespace MediaBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of MediaType.
 */
class MediaType extends \Sonata\MediaBundle\Form\Type\MediaType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }

    public function getParent()
    {
        return 'sonata_media_type';
    }
    /**
     * @return string
     */
    public function getName()
    {
        return 'template_media_type';
    }
}
