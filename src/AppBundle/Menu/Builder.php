<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 16/06/2015
 * Time: 15:16
 */

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;

class Builder extends ContainerAware
{

    public function mainMenu(FactoryInterface $factory, $request, array $options = null)
    {
        $routeName = $request->get('_route');

        $menu = $factory->createItem('mainMenu');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        $route = 'homepage';
        $class = $this->testRoute($routeName, $route);

        $menu->addChild('Tableau de bord', array('route' => $route))
            ->setAttribute('icon', 'glyphicon glyphicon-home')
            ->setAttribute('class', $class);

        $route = 'new_valuationmeet';
        $class = $this->testRoute($routeName, $route);
        $menu->addChild('Entretiens d\'appréciation', array('route' => $route))
            ->setAttribute('dropdown', true)
            ->setAttribute('class', $class);
        $menu['Entretiens d\'appréciation']->addChild('Créer', array('route' => $route));

        $route = 'new_professionalmeet';
        $class = $this->testRoute($routeName, $route);
        $menu->addChild('Entretiens professionnels', array('route' => $route))
            ->setAttribute('dropdown', true)
            ->setAttribute('class', $class);
        $menu['Entretiens professionnels']->addChild('Créer', array('route' => $route));


        // ... add more children

        return $menu;
    }

    public function userMenu(FactoryInterface $factory, $request, array $options = null)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');

        /*
        You probably want to show user specific information such as the username here. That's possible! Use any of the below methods to do this.

        if($this->container->get('security.context')->isGranted(array('ROLE_ADMIN', 'ROLE_USER'))) {} // Check if the visitor has any authenticated roles
        $username = $this->container->get('security.context')->getToken()->getUser()->getUsername(); // Get username of the current logged in user

        */
        $menu->addChild('Déconnexion', array('route' => 'arianespace_plexcel_logout'))
            ->setAttribute('icon', 'glyphicon glyphicon-off' )
            ->setAttribute('class', 'nav navbar-nav navbar-right');

        return $menu;
    }

    /**
     * Détermine si une route est la route actuelle
     * @param $actualRoute
     * @param $route
     * @return string
     *
     */
    protected function testRoute($actualRoute, $route){
        if($actualRoute === $route){
            return 'active';
        }
        return '';
    }
}