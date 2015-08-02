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

class OpusSheetStatusAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('label', 'text', array('label' => 'Intitulé'))
            ->add('intCode', 'number', array('label' => 'Code numéroté'))
            ->add('strCode', 'text', array('label' => 'Code texte'))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('label', null, array('label' => 'Intitulé'))
            ->add('intCode', null, array('label' => 'Code numéroté'))
            ->add('strCode', null, array('label' => 'Code texte'))

        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->addIdentifier('label', null, array('label' => 'Intitulé'))
            ->add('intCode', null, array('label' => 'Code numéroté'))
            ->add('strCode', null, array('label' => 'Code texte'))

        ;
    }
}
