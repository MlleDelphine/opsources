<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 10/06/2015
 * Time: 14:40
 */

namespace FormGeneratorBundle\Form\Conditions;

use FormGeneratorBundle\Form\Type\CustomCollectionType;
use FormGeneratorBundle\Form\Type\CustomRadioType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver ;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;


class ConditionsAttributeEditType extends AbstractType {

    private $security;
    protected $attributes;
    protected $em;
    protected $tab;

    public function __construct ($attributes, $em, TokenStorage $security, $tab = null)
    {
        $this->attributes = $attributes;
        $this->em = $em;
        $this->security = $security;
        $this->tab = $tab;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formFactory = $builder->getFormFactory();

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (\Symfony\Component\Form\FormEvent $event) use ($formFactory) {
                if (null != $event->getData()) {
                    $form = $event->getForm();
                    $data = $event->getData();
                    $options = array();
                    $attributes = $this->attributes;
                    foreach ($attributes as $k => $allConf) {
                        if ($allConf['id'] == $data->getName()) {
                            foreach ($allConf['conf'] as $name => $value) {
                                $options[$name] = $value;
                            }
                            //Qui est connecté et quel rôle a t-il sur la fiche ?
                            $user = $this->security->getToken()->getUser();
                            $meet = $data->getConditionsMeet();
                            //Si évalué tout est désactivé sauf les siens
                            if($meet->getAssessed() === $user){
                                $options['attr']['readonly'] = true;
                                if($allConf['conf']['attr']['data-access'] == 'assessed'){
                                    unset($options['attr']['readonly']);
                                }
                            }
                            elseif($meet->getAssessor() === $user){
                                if(isset($allConf['conf']['attr']['data-access']) && $allConf['conf']['attr']['data-access'] == 'assessed'){
                                    $options['attr']['readonly'] = true;
                                }
                            }

                            $this->tab = $allConf['conf']['attr']['data-tab'];
                            if($allConf['type'] == 'genemu_jqueryselect2_entity' || $allConf['type'] == 'entity'){
                                if($data->getValue() == null){
                                    $options['data'] = null;
                                }
                                elseif(is_array($data->getValue())){
                                    $class = $allConf['conf']['class'];
                                    $options['data'] = $this->em->getRepository($class)->findById($data->getValue());
                                    $form->add(
                                        'value',
                                        $allConf['type'],
                                        $options
                                    );
                                }else{
                                    $class = $allConf['conf']['class'];
                                    $options['data'] = $this->em->getRepository($class)->find($data->getValue());
                                    $form->add(
                                        'value',
                                        $allConf['type'],
                                        $options
                                    );
                                }
                            }
                            elseif($allConf['type'] == 'collection'){
                                $confChild = $allConf['child'];
                                $label = $allConf['conf']['label'];
                                $form->add(
                                    'collectionAttributes', new CustomCollectionType(count($confChild)), array(
                                    'type' => new ConditionsCollectionAttributeEditType($confChild, $this->em, $this->security),
                                    'allow_add' => true,
                                    'allow_delete' => true,
                                    'by_reference' => false,
                                    'required' => false,
                                    'label' => $label));
                            }
                            elseif($allConf['type'] == 'choice'){
                                $allConf['type'] = new CustomRadioType();
                                $form->add(
                                    'value',
                                    $allConf,
                                    $options
                                );
                            }
                            else{
                                $form->add(
                                    'value',
                                    $allConf['type'],
                                    $options
                                );
                            }
                        }

                    }
                    $form->add('name', 'hidden', array('label' => false, 'attr' => array('data-tab' => $this->tab)));
                    $form->add('fieldType', 'hidden', array('label' => false, 'attr' => array('data-tab' => $this->tab)));
                }
            });
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FormGeneratorBundle\Entity\ConditionsAttribute'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'formgenerator_conditions_attribute';
    }
}