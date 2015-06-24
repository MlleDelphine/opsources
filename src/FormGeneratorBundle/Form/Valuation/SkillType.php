<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 23/06/2015
 * Time: 15:00
 */
namespace FormGeneratorBundle\Form\Valuation;

use FormGeneratorBundle\Form\Type\CustomRadioType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver ;

class SkillType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array('label' => "Qualités personnelles", 'disabled' => true))
            ->add('state', 'entity', array(
                'class' => 'FormGeneratorBundle:SkillState',
                'data_class' => 'FormGeneratorBundle\Entity\SkillState',
                'property' => 'name',
                'label' => 'Etat',
                'multiple' => false,
                'expanded' => true,
                'required' => true,
                'attr' => array('class' => 'icheck icheck-radio', 'data-radio' => 'iradio_square-blue'),
                'label_attr' => array('class' => 'radio-inline')
            ))
            ->add('strongPoint', 'text', array('label' => "Point fort du salarié :"))
            ->add('comments', 'textarea', array('label' => 'Commentaire(s)'));
    }


    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FormGeneratorBundle\Entity\Skill'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'formgenerator_skill';
    }
}
