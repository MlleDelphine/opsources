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

class OpusSheetTypeAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $serviceContainer = $this->getConfigurationPool()->getContainer();
        $allConfFiles = $serviceContainer->get('app.customfields_parser')->listAllFiles();

        $formMapper
            ->add('name', 'text', array('label' => 'Intitulé'))
            ->add('strCode', 'text', array('label' => 'Code texte'))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name', null, array('label' => 'Intitulé'))
            ->add('strCode', null, array('label' => 'Code'))

        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->addIdentifier('name', null, array('label' => 'Intitulé'))
            ->add('strCode', null, array('label' => 'Code texte'))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                )
            ));

        ;
    }
}
