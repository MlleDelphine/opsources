<?php

namespace GeneratorBundle\Form\Campaign;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OpusCampaignType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('year', 'text', array('label' => 'Année *', 'required' => true))
            ->add('mailDate', 'datetime', array('label' => 'Mail date *', 'required' => true,
                    'widget' => 'single_text',
                    'input' => 'datetime',
                    'format' => 'dd/MM/yyyy',
                    'attr'=>array(
                        'class' => 'datepicker'
                    )))
            ->add('untilSheetDate', 'datetime',array('label' => 'Date de départ des fiches à rattacher *', 'required' => true,
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy',
                'attr'=>array(
                    'class' => 'datepicker'
                )))
            ->add('limitDate', 'datetime', array('label' => 'Date limite de validation', 'required' => true,
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy',
                'attr'=>array(
                    'class' => 'datepicker'
                )))
            ->add('status', 'choice', array(
                'label' => 'Statut *',
                'choices' => array(
                    0 => 'Désactivé',
                    1 => 'Activé',
                ),
                'multiple' => false,
                'required' => true ))
            ->add('opusTemplate', 'entity', array(
                'class' => 'GeneratorBundle:OpusSheetTemplate',
                'property' => 'name',
                'label' => 'Template *',
                'multiple' => false,
                'placeholder' => 'Sélectionner le template associé à cette campagne',
                'required' => true, ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GeneratorBundle\Entity\OpusCampaign'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'generatorbundle_opuscampaign';
    }
}
