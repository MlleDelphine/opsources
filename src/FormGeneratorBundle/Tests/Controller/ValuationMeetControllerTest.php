<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 26/06/2015
 * Time: 15:00
 */

namespace FormGeneratorBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
//use FormGeneratorBundle\Tests\Controller\LoadFixture;

class ValuationMeetControllerTest extends WebTestCase{

//    private $fixture;
//
//    public function __construct(){
//        $this->fixture = new LoadFixture(\FormGeneratorBundle\DataFixtures\ORM\LoadFixturesTestData::class);
//    }
//
    public function testIndex()
    {
        $client = parent::createClient();
        $crawler = $client->request('GET', $client->getContainer()->get('router')->generate('new_valuationmeet', array(), false));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
//
//    public function testCategorie()
//    {
//        $client = parent::createClient();
//        TestEntity::loadAdmin($client);
//        $testEntity = new TestEntity();
//        $crawler = $client->request('GET', $client->getContainer()->get('router')->generate('admin_blog_categorie', array('categorie'=> $testEntity->getOneCategorie()), false));
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//    }
//
//    public function __destruct(){
//        $this->fixture->destructUser();
//    }


}