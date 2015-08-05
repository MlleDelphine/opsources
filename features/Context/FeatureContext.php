<?php

namespace Context;

use Behat\Behat\Context\Context;
use mageekguy\atoum\asserter\generator as asserter;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\RawMinkContext;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Rezzza\RestApiBehatExtension\Rest\RestApiBrowser;
use Rezzza\RestApiBehatExtension\Json\JsonInspector;

class FeatureContext extends RawMinkContext implements Context, SnippetAcceptingContext
{
    /** @var asserter */
    private $asserter;

    /** @var RestApiBrowser */
    private $restApiBrowser;

    public function __construct(
        ContainerInterface $container,
        RestApiBrowser $restApiBrowser,
        JsonInspector $jsonInspector
    ) {
        $this->container = $container;
        $this->restApiBrowser = $restApiBrowser;
        $this->jsonInspector = $jsonInspector;
        $this->asserter = new asserter();
    }

    /**
     * @Given /^je remplis le champ caché "([^"]*)" avec "([^"]*)"$/
     */
    public function jeRemplisLeChampCacheAvec($field, $value)
    {
        $this->getSession()->getPage()->find('css',
            'input[name="'.$field.'"]')->setValue($value);
    }

    /**
     * @Given /^que je suis identifié en tant que "([^"]*)"$/
     */
    public function queJeSuisIdentifieEnTantQue($username)
    {
        try {
            $driver = $this->getSession()->getDriver();
            //var_dump(get_class($driver)); die;
            /*if (!$driver instanceof \Behat\Mink\Driver\BrowserKitDriver){
                throw new UnsupportedDriverActionException('This step is only supported by the BrowserKitDriver');
            }*/

            $client = $driver->getClient();
            $client->getCookieJar()->set(new Cookie(session_name(), true));

            $session = $client->getContainer()->get('session');

            $user = $this->container->get('fos_user.user_manager')->findUserByUsername($username);
            $providerKey = $this->container->getParameter('fos_user.firewall_name');

            if (empty($user)) {
                throw new \InvalidArgumentException('no user find');
            }

            $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
            $session->set('_security_'.$providerKey, serialize($token));
            $session->save();

            $cookie = new Cookie($session->getName(), $session->getId());
            $client->getCookieJar()->set($cookie);
        } catch (\Exception $e) {
            $this->getMink()->assertSession()->responseContains('---Utilisateur non trouvé '.$username.'---');
        }
    }

    /**
     * @Given /^que j'ajoute l'entête "([^"]*)" égal à "([^"]*)"$/
     */
    public function iAddHeaderEqualTo($headerName, $headerValue)
    {
        /* @var $driver \Behat\Mink\Driver\BrowserKitDriver */
        $driver = $this->getSession()->setRequestHeader($headerName, $headerValue);
    }

    /**
     * @When je charge la page en cours au format json
     */
    public function iLoadJson()
    {
        $content = $this->getSession()->getDriver()->getContent();
        $this->jsonInspector->writeJson((string) $content);
    }

    /**
     * @Then /^le noeud JSON "(?P<jsonNode>[^"]*)" devrait être égal à "(?P<expectedValue>.*)"$/
     */
    public function theJsonNodeShouldBeEqualTo($jsonNode, $expectedValue)
    {
        $realValue = $this->evaluateJsonNodeValue($jsonNode);
        $this->asserter->variable($realValue)->isEqualTo($expectedValue);
    }

    private function evaluateJsonNodeValue($jsonNode)
    {
        return $this->jsonInspector->readJsonNodeValue($jsonNode);
    }

    /**
     * @param string $method request method
     * @param string $url    relative url
     *
     * @When /^(?:j')?envoie une requête ([A-Z]+) sur "([^"]+)"$/
     */
    public function iSendARestRequest($method, $url)
    {
        //dump($this->restApiBrowser); die;
        $client = $this->getSession()->getDriver()->getClient();
        $client->request($method, $url, array(), array(), array());
    }

    /**
     * Returns fixed step argument (with \\" replaced back to ").
     *
     * @param string $argument
     *
     * @return string
     */
    protected function fixStepArgument($argument)
    {
        return str_replace('\\"', '"', $argument);
    }
}
