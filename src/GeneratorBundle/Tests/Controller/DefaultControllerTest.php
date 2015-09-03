<?php
/**
 * Created by PhpStorm.
 * User: Jordi
 * Date: 10/08/2015
 * Time: 15:11
 */

namespace GeneratorBundle\Tests\Controller;

use GeneratorBundle\Entity\OpusSheetTemplate;
use MediaBundle\Entity\Media;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Yaml\Parser;

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
        $this->logInAs('ROLE_ADMIN');
        $this->loadYML();
//        $crawler = $this->client->request('GET', 'edit/sheet/21680');
//        dump($client);die();
//        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());
    }

    public function loadYML(){
//        $em = $this->em;
//
//        $yaml = new Parser();
//        try {
//            $file = $yaml->parse(file_get_contents(static::$kernel->getRootDir().'/../app/config/BaseFormMeet/test_form.yml'));
//        } catch (ParseException $e) {
//            printf("Unable to parse the YAML string: %s", $e->getMessage());
//        }
//
//        $file = file_get_contents(static::$kernel->getRootDir().'/../app/config/BaseFormMeet/test_form.yml');
//
//        dump($file);die();
//
//        $media = new Media();
//        $media
//            ->setName('TestForm')
//            ->setProviderMetadata($file)
//            ->setProviderName('providerTest.txt');
//
//        $opusSheetTemplate = new OpusSheetTemplate();
//        $opusSheetTemplate
//            ->setStatus(1)
//            ->setName('TestForm')
//            ->setConfFile($media);
//
//        $em->persist($media);
//        $em->persist($opusSheetTemplate);
//        $em->flush();
    }


}