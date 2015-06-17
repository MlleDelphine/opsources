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

//        $menu->addChild('Accueil', array('route' => 'homepage'));
//
//        // access services from the container!
//        $em = $this->container->get('doctrine')->getManager();
//        // findMostRecent and Blog are just imaginary examples
//        $blog = $em->getRepository('FormGeneratorBundle:Blog')->findMostRecent();
//
//        $menu->addChild('Latest Blog Post', array(
//            'route' => 'blog_show',
//            'routeParameters' => array('id' => $blog->getId())
//        ));
//
//        // create another menu item
//        $menu->addChild('About Me', array('route' => 'about'));
//        // you can also add sub level's to your menu's as follows
//        $menu['About Me']->addChild('Edit profile', array('route' => 'edit_profile'));
        $menu->addChild('Test', array('route' => 'homepage' ))
              ->setAttribute('icon', 'glyphicon glyphicon-home');

        $menu->addChild('Entretiens d\'appréciation', array('route' => 'homepage'))
              ->setAttribute('dropdown', true);
        $menu['Entretiens d\'appréciation']->addChild('Créer une évaluation', array('route' => 'new_valuationmeet'));


        // ... add more children

        return $menu;
    }
}