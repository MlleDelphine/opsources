<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 03/06/2015
 * Time: 12:08
 */
namespace GeneratorBundle\Form\Conditions;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use FormGeneratorBundle\Form\Conditions\OpusSheetAttributeNewType;
use GeneratorBundle\Entity\WorkCondition;
use UserBundle\Entity\Repository\UserRepository;
use GeneratorBundle\Form\Type\CustomCollectionAttributeType;
use GeneratorBundle\Form\Type\CustomCollectionType;
use GeneratorBundle\Form\Type\CustomCollectionFieldType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver ;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;


class OpusSheetType extends AbstractType{

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
            FormEvents::PRE_SET_DATA,
            function (\Symfony\Component\Form\FormEvent $event) use ($user) {
                $meet = $event->getData();
                $form = $event->getForm();
                $validatedE = $this->em->getRepository('GeneratorBundle:Status')->findOneBy(array('code' =>"validated_e"));
                if($meet->getEvaluator()){
                    $evaluator = $meet->getEvaluator();
                }
                else{
                    $evaluator = $user;
                }
                //Si évalué tout est désactivé sauf les siens
                $access = true;

                if($meet->getEvaluator() === $user && $meet->getStatus() ==  $validatedE){
                    $attr = array('data-tab' => 'tab_1', 'readonly' => true);
                    $access = false;
                }
                elseif($meet->getEvaluate() === $user){
                    $attr = array('data-tab' => 'tab_1', 'readonly' => true);
                    $access = false;
                }
                elseif($meet->getEvaluator() === $user){
                    $attr = array('data-tab' => 'tab_1');
                }
                else{
                    $attr = array('data-tab' => "tab_1");
                }

//                $form->add('name', 'text', array(
//                    'label' => 'Nom :',
//                    'attr' => $attr))
//                    ->add('meetDate', 'genemu_jquerydate', array(
//                        'label' => 'Date de l\'entretien  :',
//                        'attr' => $attr,
//                        'widget' => 'single_text'));

                $form->add('evaluator', 'genemu_jqueryselect2_entity', array(
                        'class' => 'UserBundle:User',
                        'query_builder' => function (UserRepository $er) use ($evaluator) {
                            return $er->findOneByQuery($evaluator);
                        },
                        'label' => 'Evaluateur',
                        'multiple' => false,
                        'placeholder' => 'Sélectionner',
                        'required' => true,
                        'attr' => $attr)
                );
                if(!$meet->getEvaluate() || $access == true) {
                    //On affiche tous les users(sauf le connecté qui est le manager) = création
                    $form->add(
                        'evaluate',
                        'genemu_jqueryselect2_entity',
                        array(
                            'class' => 'UserBundle:User',
                            'query_builder' => function (UserRepository $er) use ($user) {
                                return $er->findAllExcept($user);
                            },
                            'label' => 'Evalué',
                            'multiple' => false,
                            'placeholder' => 'Sélectionner',
                            'required' => true,
                            'attr' => $attr
                        )
                    );
                }
                else{
                    $evaluate = $meet->getEvaluate();
                    //On laisse le connecté de la liste des évalués potentiels
                    $form->add(
                        'evaluate',
                        'genemu_jqueryselect2_entity',
                        array(
                            'class' => 'UserBundle:User',
                            'query_builder' => function (UserRepository $er) use ($evaluate) {
                                return $er->findOneByQuery($evaluate);
                            },
                            'label' => 'Evalué',
                            'multiple' => false,
                            'placeholder' => 'Sélectionner',
                            'required' => true,
                            'attr' => $attr
                        )
                    );

                }
                $attr['data-tab'] = "tab_2";
               // unset($attr['disabled']);
//                $form->add('workConditions', new CustomCollectionFieldType(3), array(
//                    'type' => new WorkConditionType($access),
//                    'allow_add' => true,
//                    'allow_delete' => false,
//                    'by_reference' => false,
//                    'required' => false,
//                    'label' => false,
//                    'attr' => $attr
//                ));

                //Nouveau champs de formulaire : attributs simples
                if (!$event || null === $meet->getId()) {
                    $form->add(
                        'attributes', new CustomCollectionAttributeType(), array(
                        'type' => new OpusSheetAttributeNewType($this->attributes['attr']),
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
                            'type' => new OpusSheetAttributeEditType($this->attributes['attr'], $this->em, $this->security),
                            'allow_add' => true,
                            'allow_delete' => false,
                            'by_reference' => false,
                            'required' => false,
                            'label' => false
                        )
                    );
                }

                //Nouveau champs de formulaire : collection d'attributs
                if (!$event || null === $meet->getId()) {
                    $form->add(
                        'collections', new CustomCollectionAttributeType(), array(
                        'type' => new OpusSheetCollectionAttributeNewType($this->attributes['collections']),
                        'allow_add' => true,
                        'allow_delete' => true,
                        'by_reference' => false,
                        'required' => false,
                        'label' => ''));
                }
                //Edition d'un formulaire existant
                else{
                    $form->add(
                        'collections',  new CustomCollectionAttributeType(), array(
                            'type' => new OpusSheetCollectionAttributeEditType($this->attributes['attr'], $this->em, $this->security),
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
            'data_class' => 'GeneratorBundle\Entity\OpusSheet'
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
