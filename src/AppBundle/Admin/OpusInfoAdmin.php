<?php

/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 23/06/2015
 * Time: 14:31.
 */

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class OpusInfoAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $serviceContainer = $this->getConfigurationPool()->getContainer();
        $allConfFiles = $serviceContainer->get('app.customfields_parser')->listAllFiles();

        $formMapper
            ->add('year', 'text', array('label' => 'Année'))
            ->add('mailDate', 'date', array('label' => 'Mail date'))
            ->add('limitDate', 'date', array('label' => 'Date limite'))
            ->add('status', 'choice', array('label' => 'Statut', 'choices' => array(0 => 'Désactivée', 1 => 'Activée')))
            ->add('type', 'entity', array('class' => 'GeneratorBundle:OpusSheetType',
                'label' => 'Statut',
                'property' => 'name', ))
            ->add('opusTemplate', 'entity')
//            ->add('confFile', 'choice', array('label' => "Fichier de configuration", "choices" => $allConfFiles ))
//            ->add('confFileUi', 'choice', array('label' => "Fichier de configuration UI", "choices" => $allConfFiles ))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('year', null, array('label' => 'Année'))
            ->add('mailDate', null, array('label' => 'Mail date'))
            ->add('limitDate', null, array('label' => 'Date limite'))
            ->add('status', null, array('label' => 'Statut'))
            ->add('type', null, array('label' => "Type d'entretien"))
//            ->add('confFile', null, array('label' => "Fichier de configuration"))
//            ->add('confFileUi', null, array('label' => "Fichier de configuration UI"))

        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->addIdentifier('year', null, array('label' => 'Année'))
            ->add('mailDate', null, array('label' => 'Mail date'))
            ->add('limitDate', null, array('label' => 'Date limite'))
            ->add('status', null, array('label' => 'Statut'))
            ->add('type', null, array('label' => "Type d'entretien"))
//            ->add('confFile', null, array('label' => "Fichier de configuration"))
//            ->add('confFileUi', null, array('label' => "Fichier de configuration UI"))
        ;
    }
}
