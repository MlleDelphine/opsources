<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 03/06/2015
 * Time: 12:08
 */
namespace FormGeneratorBundle\Form\Conditions;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use FormGeneratorBundle\Entity\WorkCondition;
use UserBundle\Entity\Repository\UserRepository;
use FormGeneratorBundle\Form\Type\CustomCollectionAttributeType;
use FormGeneratorBundle\Form\Type\CustomCollectionType;
use FormGeneratorBundle\Form\Type\CustomCollectionFieldType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver ;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;


class ConditionsMeetType extends AbstractType{

    protected $attributes;
    protected $em;
    private $security;

    public function __construct ($attributes, EntityManager $em, TokenStorage $security)
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

        $user = $this->security->getToken()->getUser();

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (\Symfony\Component\Form\FormEvent $event) use ($user) {
                $meet = $event->getData();
                $form = $event->getForm();
                //Si évalué tout est désactivé sauf les siens
                $access = true;

                if($meet->getAssessed() === $user){
                    $attr = array('data-tab' => 'tab_1', 'disabled' => true);
                    $access = false;
                }
                elseif($meet->getAssessor() === $user){
                    $attr = array('data-tab' => 'tab_1');

                }
                else{
                    $attr = array('data-tab' => "tab_1");

                }

                $form->add('name', 'text', array(
                    'label' => 'Nom :',
                    'attr' => $attr))
                    ->add('meetDate', 'genemu_jquerydate', array(
                        'label' => 'Date de l\'entretien  :',
                        'attr' => $attr,
                        'widget' => 'single_text'))
                    ->add('assessor', 'genemu_jqueryselect2_entity', array(
                        'class' => 'UserBundle:User',
                        'label' => 'Evaluateur',
                        'multiple' => false,
                        'placeholder' => 'Sélectionner',
                        'required' => false,
                        'disabled' => true,
                        'attr' => $attr)
                );
                if($meet->getAssessed() === $user) {
                    //On s'occupe de retirer le connecté de la liste des évalués potentiels
//                    $form->add(
//                        'assessed',
//                        'genemu_jqueryselect2_entity',
//                        array(
//                            'class' => 'UserBundle:User',
//                            'label' => 'Evalué',
//                            'multiple' => false,
//                            'placeholder' => 'Sélectionner',
//                            'required' => false,
//                            'attr' => $attr
//                        )
//                    );
                }
                else{
                    //On affiche le connecté de la liste des évalués (car il a été choisi et c'est l'évalué qui est connecté)
                    $form->add(
                        'assessed',
                        'genemu_jqueryselect2_entity',
                        array(
                            'class' => 'UserBundle:User',
                            'query_builder' => function (UserRepository $er) use ($user) {
                                return $er->findAllExcept($user);
                            },
                            'label' => 'Evalué',
                            'multiple' => false,
                            'placeholder' => 'Sélectionner',
                            'required' => false,
                            'attr' => $attr
                        )
                    );

                }
                $attr['data-tab'] = "tab_2";
                $form->add('workConditions', new CustomCollectionFieldType(3), array(
                    'type' => new WorkConditionType($access),
                    'allow_add' => true,
                    'allow_delete' => false,
                    'by_reference' => false,
                    'required' => false,
                    'label' => false,
                    'attr' => $attr
                ));

                //Nouveau champs de formulaire
                if (!$event || null === $meet->getId()) {
                    $form->add(
                        'attributes', new CustomCollectionAttributeType(), array(
                        'type' => new ConditionsAttributeNewType($this->attributes['attr']),
                        'allow_add' => true,
                        'allow_delete' => true,
                        'by_reference' => false,
                        'required' => false,
                        'label' => ''));
                }
                //Edition d'un formulaire existant
                else{
                    $form->add(
                        'attributes',  new CustomCollectionAttributeType(), array(
                            'type' => new ConditionsAttributeEditType($this->attributes['attr'], $this->em, $this->security),
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
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FormGeneratorBundle\Entity\ConditionsMeet'
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
