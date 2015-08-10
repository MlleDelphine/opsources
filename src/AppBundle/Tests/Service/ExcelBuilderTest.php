<?php
/**
 * Created by PhpStorm.
 * User: Jordi
 * Date: 10/08/2015
 * Time: 15:11
 */

namespace AppBundle\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExcelBuilderTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    public function testSearchByCategoryName()
    {
        $headers = array('ID','Firstname','Lastname');
        $data = [
            array('0','Jordi','Ferte'),
            array('1','Paul','Pasturel'),
            array('2','Nicolas','de Marque'),
            array('3','Delphine','Graftieaux'),
        ];

        /**
         * @var \AppBundle\Service\ExcelBuilder $phpExcelService
         */
        $phpExcelService = static::$kernel->getContainer()->get("app.excelbuilder");
        $worksheet = $phpExcelService->createWorkSheet();

        $worksheet->getProperties()
            ->setCreator("liuggio")
            ->setLastModifiedBy("Giulio De Donato")
            ->setTitle("Office 2005 XLSX Test Document")
            ->setSubject("Office 2005 XLSX Test Document")
            ->setDescription($description="Test document for Office 2005 XLSX, generated using PHP classes.")
            ->setKeywords("office 2005 openxml php")
            ->setCategory("Test result file")
            ->setModified('2009-02-15 15:16:17')
            ->setCreated('2009-02-15 15:16:17');


        $phpExcelService->draw($headers, $data, 0, true);

        $phpExcelService->writeFile(__DIR__.'/test.xls');

        $this->assertFileExists(__DIR__.'/test.xls');
        $this->assertEquals(filesize(__DIR__.'/test.xls'),filesize(__DIR__.'/testExportReference.xls'));

        $creadtedExcel = static::$kernel->getContainer()->get('phpexcel')->createPHPExcelObject(__DIR__.'/test.xls');
        $this->assertEquals($creadtedExcel->getProperties()->getDescription(),$description);
    }
}