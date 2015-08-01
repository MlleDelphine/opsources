<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 03/06/2015
 * Time: 12:08
 */
namespace GeneratorBundle\Form\Sheets;

use FormGeneratorBundle\Form\Type\CustomCollectionAttributeType;
use GeneratorBundle\Form\Type\CustomRadioType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver ;
use Symfony\Component\Form\FormEvents;
use FormGeneratorBundle\Form\Type\CustomCollectionType;

class OpusSheetAttributeNewType extends AbstractType{

    protected $attributes;
    protected $tab;

    public function __construct ($attributes, $tab = null)
    {
        $this->attributes = $attributes;
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
                    $valAttributeEntity = $event->getData();
                    if (!$event || null === $valAttributeEntity->getId()) {
                        $form = $event->getForm();
                        $data = $event->getData();
                        $options = array();
                        $confChild = false;
                        $fieldName = "value";
                        foreach ($this->attributes as $allConf) {
                         //   dump($allConf);
                            if ($allConf['id'] == $data->getLabel()) {
                                //Si on a un champ date  /time on stockera dans valueDate sinon dans value
                                if($allConf['type'] == "date" || $allConf['type'] == "datetime" || $allConf['type'] == "time" || $allConf['type']== "birthday" || $allConf['type'] =="genemu_jquerydate" ){
                                    $fieldName = "valueDate";
                                }
                                elseif($allConf['type'] == "file"){
                                    $fieldName = "valueData";
                                }

                                if($allConf['type'] == 'choice'){
                                    $allConf['type'] = new CustomRadioType();
                                }
                                foreach ($allConf['conf'] as $name => $value) {
                                    $options[$name] = $value;
                                }
                                $this->tab = $allConf['conf']['attr']['data-tab'];

                                //Seul le manager peut remplir certains champs
                                if(isset($allConf['conf']['attr']['data-access']) && $allConf['conf']['attr']['data-access'] == 'evaluate'){
                                    $options['disabled'] = true;
                                    $options['attr']['readonly'] = true;
                                }

                                $form->add(
                                    $fieldName,
                                    $allConf['type'],
                                    $options
                                );

                                $form->add('label', 'hidden', array('label' => false, 'attr' => array('data-tab' => $this->tab)));
                            }
                        }
                    }
                }
            });
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GeneratorBundle\Entity\OpusAttribute'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'generator_sheet_attribute';
    }
}