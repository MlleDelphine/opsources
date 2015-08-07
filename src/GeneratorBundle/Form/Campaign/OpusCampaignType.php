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
            ->add('mailDate', 'date', array('label' => 'Mail date *', 'required' => true))
            ->add('untilSheetDate', 'date',array('label' => 'Date fiche *', 'required' => true))
            ->add('limitDate', 'date', array('label' => 'Date limite *', 'required' => true))
            ->add('status', 'choice', array(
                'label' => 'Statut *',
                'choices' => array(
                    0 => 'Désactivée',
                    1 => 'Activée',
                ),
                'multiple' => false,
                'required' => true ))
//            ->add('type', 'entity', array('class' => 'GeneratorBundle:OpusSheetType',
//                'label' => "Type d'entretien *",
//                'property' => 'name', ))
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
