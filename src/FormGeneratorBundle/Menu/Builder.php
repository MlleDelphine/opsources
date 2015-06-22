<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 16/06/2015
 * Time: 15:16
 */

namespace FormGeneratorBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;

class Builder extends ContainerAware
{

    public function mainMenu(FactoryInterface $factory, Request $request, array $options = null)
    {
        $menu = $factory->createItem('mainMenu');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        $menu->addChild('Tableau de bord', array('route' => 'new_valuationmeet'))
              ->setAttribute('icon', 'glyphicon glyphicon-home');

        $menu->addChild('Entretiens d\'appréciation', array('route' => 'new_valuationmeet'))
              ->setAttribute('dropdown', true);
        $menu['Entretiens d\'appréciation']->addChild('Créer', array('route' => 'new_valuationmeet'));

        $menu->addChild('Entretiens professionnels', array('route' => 'new_professionalmeet'))
            ->setAttribute('dropdown', true);
        $menu['Entretiens professionnels']->addChild('Créer', array('route' => 'new_professionalmeet'));


        // ... add more children

        return $menu;
    }
}