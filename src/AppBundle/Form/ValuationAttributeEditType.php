<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 10/06/2015
 * Time: 14:40
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver ;
use Symfony\Component\Form\FormEvents;


class ValuationAttributeEditType  extends AbstractType {

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
                            switch ($allConf['type']):
                                case 'entity':
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
                                    break;
                                case 'collection':
                                    //do some stuff
                                    $confChild = $allConf['child'];
                                    $label = $allConf['conf']['label'];
                                    $form->add(
                                        'collectionAttributes', 'collection', array(
                                        'type' => new ValuationCollectionAttributeEditType($confChild, $this->em),
                                        'allow_add' => true,
                                        'allow_delete' => true,
                                        'by_reference' => false,
                                        'required' => false,
                                        'label' => $label));
                                    break;
                                default:
                                    $form->add(
                                        'value',
                                        $allConf['type'],
                                        $options
                                    );
                            endswitch;
                        }

                    }
                    $form->add('name', 'hidden');
                    $form->add('fieldType', 'hidden');
                }
            });
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ValuationAttribute'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'formgenerator_valuation_attribute';
    }
}