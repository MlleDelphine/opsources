<?php

/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 29/07/2015
 * Time: 10:13.
 */

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class OpusSheetTemplateAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', 'text', array('label' => 'Intitulé'))
            ->add('type', 'entity', array('class' => 'GeneratorBundle:OpusSheetType',
                'label' => 'Statut',
                'property' => 'name', ))
            ->add('status', 'choice', array('label' => 'Statut', 'choices' => array(0 => 'Désactivée', 1 => 'Activée')))
            ->add('confFile', 'sonata_media_type', array('required' => false,
                'provider' => 'sonata.media.provider.file',
                'context' => 'default', ))

        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name', null, array('label' => 'Intitulé'))
            ->add('type', null, array('Label' => 'Type'))
            ->add('status', null, array('label' => 'Statut', 'choices' => array(0 => 'Désactivé', 1 => 'Activé')));
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->addIdentifier('name', null, array('label' => 'Intitulé'))
            ->add('type', null, array('Label' => 'Type'))
            ->add('status', null, array('label' => 'Statut'));
    }
}
