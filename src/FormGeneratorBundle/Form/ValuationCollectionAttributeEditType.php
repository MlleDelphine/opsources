<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 11/06/2015
 * Time: 16:30
 */

namespace FormGeneratorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver ;
use Symfony\Component\Form\FormEvents;


class ValuationCollectionAttributeEditType extends AbstractType {

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

                    foreach ($this->attributes as $allConf) {
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
            'data_class' => 'FormGeneratorBundle\Entity\ValuationCollectionAttribute'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'formgenerator_valuation_collectionattribute';
    }
}