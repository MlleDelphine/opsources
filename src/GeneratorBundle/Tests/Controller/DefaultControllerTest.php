<?php
/**
 * Created by PhpStorm.
 * User: Jordi
 * Date: 10/08/2015
 * Time: 15:11
 */

namespace GeneratorBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    private $client;

    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->client = static::createClient();
    }

    protected function logInAs($role)
    {
        $session = $this->client->getContainer()->get('session');

        $firewall = 'secured_area';
        $token = new UsernamePasswordToken('admin', null, $firewall, array($role));
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    public function testGenerationForEvaluator()
    {

        $this->logIn('ROLE_ADMIN');

        $crawler = $this->client->request('GET', 'edit/sheet/21680');
//        dump($client);die();
        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());
    }


    public function testGenerationForEvaluate()
    {

    }
}