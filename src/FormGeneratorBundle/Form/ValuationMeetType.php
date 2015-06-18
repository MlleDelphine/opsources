<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 03/06/2015
 * Time: 12:08
 */
namespace FormGeneratorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver ;

class ValuationMeetType extends AbstractType{

    protected $attributes;
    protected $em;

    public function __construct ($attributes, $em)
    {
        $this->attributes = $attributes;
        $this->em = $em;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formFactory = $builder->getFormFactory();
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (\Symfony\Component\Form\FormEvent $event) use ($formFactory) {

                $meet = $event->getData();
                $form = $event->getForm();
                //Nouveau formulaire
                if (!$event || null === $meet->getId()) {
                    $form->add(
                        'attributes', 'collection', array(
                        'type' => new ValuationAttributeNewType($this->attributes['attr']),
                        'allow_add' => true,
                        'allow_delete' => true,
                        'by_reference' => false,
                        'required' => false,
                        'label' => false));
                }
                //Edition d'un formulaire existant
                else{
                    $form->add(
                        'attributes', 'collection', array(
                            'type' => new ValuationAttributeEditType($this->attributes['attr'], $this->em),
                            'allow_add' => true,
                            'allow_delete' => false,
                            'by_reference' => false,
                            'required' => false,
                            'label' => false
                        )
                    );
                }

            }
        );

        $builder
            ->add('name', 'text', array('label' => 'Nom :'))

        ;


    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FormGeneratorBundle\Entity\ValuationMeet'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'formgenerator_valuation_meet';
    }
}
