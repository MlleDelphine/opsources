<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 03/06/2015
 * Time: 12:08
 */
namespace FormGeneratorBundle\Form\Valuation;

use FormGeneratorBundle\Form\Type\CustomCollectionAttributeType;
use FormGeneratorBundle\Form\Type\CustomCollectionType;
use FormGeneratorBundle\Form\Type\CustomCollectionFieldType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver ;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ValuationMeetType extends AbstractType{

    protected $attributes;
    protected $em;
    private $security;

    public function __construct ($attributes, $em, TokenStorage $security)
    {
        $this->attributes = $attributes;
        $this->em = $em;
        $this->security = $security;
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
                        'attributes', new CustomCollectionAttributeType(), array(
                        'type' => new ValuationAttributeNewType($this->attributes['attr'], $this->security),
                        'allow_add' => true,
                        'allow_delete' => true,
                        'by_reference' => false,
                        'required' => false,
                        'label' => false));
                }
                //Edition d'un formulaire existant
                else{
                    $form->add(
                        'attributes',  new CustomCollectionAttributeType(), array(
                            'type' => new ValuationAttributeEditType($this->attributes['attr'], $this->em, $this->security),
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
            ->add('name', 'text', array('label' => 'Nom :', 'attr' => array('data-tab'  => 'tab_1')))
            ->add('meetDate', 'genemu_jquerydate', array('label' => 'Date de l\'entretien  :', 'attr' => array('class' => 'datepicker', 'data-tab'  => 'tab_1'), 'widget' => 'single_text'))
            ->add('assessor', 'genemu_jqueryselect2_entity', array(
                'class' => 'UserBundle:User',
                'label' => 'Evaluateur',
                'multiple' => false,
                'placeholder' => 'Sélectionner',
                'required' => false,
                'attr' => array('data-tab'  => 'tab_1', 'disabled' => true)
            ))
            ->add('assessed', 'genemu_jqueryselect2_entity', array(
                'class' => 'UserBundle:User',
                'label' => 'Evalué',
                'multiple' => false,
                'placeholder' => 'Sélectionner',
                'required' => false,
                'attr' => array('data-tab'  => 'tab_1')
            ))
            ->add('skills', new CustomCollectionFieldType(3), array(
                'type' => new SkillType(),
                'allow_add' => true,
                'allow_delete' => false,
                'by_reference' => false,
                'required' => false,
                'label' => false,
                'attr' => array('data-tab'  => "tab_2")
            ))
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
