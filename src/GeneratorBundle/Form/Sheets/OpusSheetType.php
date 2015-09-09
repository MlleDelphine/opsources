<?php

/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 03/06/2015
 * Time: 12:08.
 */

namespace GeneratorBundle\Form\Sheets;

use Doctrine\ORM\EntityManager;
use GeneratorBundle\Entity\Repository\OpusJobRepository;
use GeneratorBundle\Service\AccessControlSheet;
use UserBundle\Entity\Repository\UserRepository;
use GeneratorBundle\Form\Type\CustomCollectionAttributeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class OpusSheetType extends AbstractType
{
    protected $attributes;
    protected $em;
    private $security;
    private $accessControl;

    public function __construct($attributes, EntityManager $em, TokenStorage $security, AccessControlSheet $accessControl  )
    {
        $this->attributes = $attributes;
        $this->em = $em;
        $this->security = $security;
        $this->accessControl = $accessControl;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->security->getToken()->getUser();
        $user = $this->em->getRepository('UserBundle:User')->find($user->getId());


        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (\Symfony\Component\Form\FormEvent $event) use ($user) {
                $sheet = $event->getData();
                $job1 = $sheet->getJob1();
                $job2 = $sheet->getJob2();
                $form = $event->getForm();
                //Statuts possibles pour déterminer quels submit on affiche et SI on les affiche
                $generatedStatus =  $this->em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode('generee');
                $creationStatus =  $this->em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode('creation');
                $vEvaluatedStatus =  $this->em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode('valid_evaluated');
                $vEvaluatorStatus =  $this->em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode('valid_evaluator');
                $vFinalEvaluatorStatus = $this->em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode(
                    'valid_final_evaluator'
                );
                $vRHStatus = $this->em->getRepository("GeneratorBundle:OpusSheetStatus")->findOneByStrCode(
                    'valid_RH'
                );

                //Si évalué tout est désactivé sauf les siens

                //On appelle le service qui détermine ce qui peut être édité
                $attr = array('data-tab' => 'tab_1');
                $access = $this->accessControl->determineWriteRight($sheet);

                //Si aucun accès ou au tour de l'évalué les champs principaux sont bloqués
                if ($access == "none" || $access == "evaluate_write" || $access == "drh_decision") {
                    $attr['readonly'] = true;
                }

                $form->add('evaluator', 'genemu_jqueryselect2_entity', array(
                        'class' => 'UserBundle:User',
                        'query_builder' => function (UserRepository $er) use ($sheet) {
                            return $er->findOneByQuery($sheet->getEvaluator());
                        },
                        'label' => 'Evaluateur',
                        'multiple' => false,
                        'required' => true,
                        'attr' => $attr, )
                );

                $evaluate = $sheet->getEvaluate();
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
                        'required' => true,
                        'attr' => $attr,
                    )
                );


                if($access == "none" || $access == "evaluate_write" || $access == "drh_decision"){
                    if($job1) {
                        $form->add(
                            'job1',
                            'genemu_jqueryselect2_entity',
                            array(
                                'class' => 'GeneratorBundle:OpusJob',
                                'query_builder' => function (OpusJobRepository $er) use ($job1) {
                                    return $er->findOneByQuery($job1);
                                },
                                'label' => 'Métier 1',
                                'multiple' => false,
                                'required' => true,
                                'attr' => $attr,
                            )
                        );
                    }

                    if($job2){
                        $form->add(
                            'job2',
                            'genemu_jqueryselect2_entity',
                            array(
                                'class' => 'GeneratorBundle:OpusJob',
                                'query_builder' => function (OpusJobRepository $er) use ($job2) {
                                    return $er->findOneByQuery($job2);
                                },
                                'label' => 'Métier 2',
                                'multiple' => false,
                                'required' => true,
                                'attr' => $attr,
                            ));
                    }
                }
                //Donc évaluateur
                else{
                    $form->add(
                        'job1',
                        'genemu_jqueryselect2_entity',
                        array(
                            'class' => 'GeneratorBundle:OpusJob',
                            'label' => 'Métier 1',
                            'multiple' => false,
                            'required' => true,
                            'attr' => $attr,
                        ));

                    $form->add(
                        'job2',
                        'genemu_jqueryselect2_entity',
                        array(
                            'class' => 'GeneratorBundle:OpusJob',
                            'label' => 'Métier 2',
                            'multiple' => false,
                            'required' => false,
                            'attr' => $attr,
                        ));


                }

                if (isset($this->attributes['attr'])) {
                    $form->add(
                        'attributes', new CustomCollectionAttributeType(), array(
                            'type' => new OpusSheetAttributeNewType($this->attributes['attr'], $access),
                            'allow_add' => true,
                            'allow_delete' => false,
                            'by_reference' => false,
                            'required' => false,
                            'label' => false,
                        )
                    );
                }
                //Nouveau champs de formulaire : collection d'attributs
                if (array_key_exists('collections', $this->attributes)) {
                    $form->add(
                        'collections',  new CustomCollectionAttributeType(), array(
                            'type' => new OpusSheetCollectionAttributeNewType($this->attributes['collections'], $access),
                            'allow_add' => true,
                            'allow_delete' => false,
                            'by_reference' => false,
                            'required' => false,
                            'label' => false,
                        )
                    );
                }

                if($access != "none" || ($sheet->getEvaluator()->getId() == $user->getId() && $sheet->getStatus() == $vFinalEvaluatorStatus )) {
                    //gestion des boutons de formulaire
                    if($access != "drh_decision"){
                        if ($sheet->getStatus() == $generatedStatus || $sheet->getStatus() == $creationStatus) {

                            $form->add(
                                'save',
                                'submit',
                                array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-lg btn-info'))
                            );
                            $form->add(
                                'validate',
                                'submit',
                                array('label' => 'Valider', 'attr' => array('class' => 'btn btn-lg btn-success'))
                            );


                        } elseif ($sheet->getStatus() == $vEvaluatedStatus) {
                            $form->add(
                                'invalidate',
                                'submit',
                                array('label' => 'Invalider', 'attr' => array('class' => 'btn btn-lg btn-danger'))
                            );
                            $form->add(
                                'validate',
                                'submit',
                                array('label' => 'Valider', 'attr' => array('class' => 'btn btn-lg btn-success'))
                            );
                        } elseif ($sheet->getStatus() == $vEvaluatorStatus) {
                            $form->add(
                                'invalidate',
                                'submit',
                                array('label' => 'Invalider', 'attr' => array('class' => 'btn btn-lg btn-danger'))
                            );
                            $form->add(
                                'validate',
                                'submit',
                                array('label' => 'Valider', 'attr' => array('class' => 'btn btn-lg btn-success'))
                            );
                        } elseif ($sheet->getStatus() == $vFinalEvaluatorStatus) {
                            $form->add(
                                'invalidate_for_rh',
                                'submit',
                                array('label' => 'Invalider', 'attr' => array('class' => 'btn btn-lg btn-danger'))
                            );
                            $form->add(
                                'validate_rh',
                                'submit',
                                array('label' => 'Valider pour RH', 'attr' => array('class' => 'btn btn-lg btn-success'))
                            );
                        }else {
                            $form->add(
                                'validate',
                                'submit',
                                array('label' => 'Valider', 'attr' => array('class' => 'btn btn-lg btn-success'))
                            );
                        }
                    }
                    //donc si RH ou admin
                    else {
                        if ($sheet->getStatus() == $vRHStatus) {
                            $form->add(
                                'invalidate_by_rh',
                                'submit',
                                array('label' => 'Invalider', 'attr' => array('class' => 'btn btn-lg btn-danger'))
                            );
                            $form->add(
                                'validate_by_rh',
                                'submit',
                                array('label' => 'Validation finale', 'attr' => array('class' => 'btn btn-lg btn-success'))
                            );
                        }
                    }
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
            'data_class' => 'GeneratorBundle\Entity\OpusSheet',
            'csrf_protection' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'generator_sheet';
    }
}
