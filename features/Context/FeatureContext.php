<?php

namespace Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\RawMinkContext;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class FeatureContext extends RawMinkContext implements Context, SnippetAcceptingContext
{
    public function __construct(\Symfony\Component\DependencyInjection\ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @Given /^que je suis identifié en tant que "([^"]*)"$/
     */
    public function queJeSuisIdentifieEnTantQue($username)
    {
        $driver = $this->getSession()->getDriver();
        //var_dump(get_class($driver)); die;
        /*if (!$driver instanceof \Behat\Mink\Driver\BrowserKitDriver){
            throw new UnsupportedDriverActionException('This step is only supported by the BrowserKitDriver');
        }*/

        $client = $driver->getClient();
        $client->getCookieJar()->set(new Cookie(session_name(), true));

        $session = $client->getContainer()->get('session');

        $user        = $this->container->get('fos_user.user_manager')->findUserByUsername($username);
        $providerKey = $this->container->getParameter('fos_user.firewall_name');

        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
        $session->set('_security_' . $providerKey, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }

}
