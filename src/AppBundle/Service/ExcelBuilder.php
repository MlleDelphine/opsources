<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ExcelBuilder{

    /**
     * @var \Liuggio\ExcelBundle\Factory
     */
    protected $phpExcel;

    /**
     * @var \PHPExcel
     */
    protected $worksheet;

    public function __construct()
    {

    }

    public function setExcel($phpExcel)
    {
        $this->phpExcel = $phpExcel;

        return $this;
    }

    /**
     * @return \PHPExcel
     */
    public function createWorkSheet()
    {
        $this->worksheet = $this->phpExcel->createPHPExcelObject();

        return $this->worksheet;
    }

    public function getProperties()
    {
        if(empty($this->worksheet)){
            throw new \Exception('This worksheet should be create with the method createWorkSheet before getProperties');
        }

        return $this->worksheet->getProperties();
    }

    public function draw($headers, $data, $page = 0, $autoSize = false)
    {
        $activeSheet = $this->worksheet->setActiveSheetIndex($page);

        $this->drawHeader($headers, $activeSheet);
        $this->drawData($data, $activeSheet);

        if($autoSize){
            $this->autoSize($activeSheet);
        }
    }


    public function createAndGetResponse($filename, $version = 'Excel5'){
        $writer = $this->phpExcel->createWriter($this->worksheet, $version);
        $response = $this->phpExcel->createStreamedResponse($writer);

        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );

        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);


        return $response;
    }

    public function writeFile($filename, $version = 'Excel5'){
        $writer = $this->phpExcel->createWriter($this->worksheet, $version);
        $writer->save($filename);
    }

    protected function drawHeader($headers, $activeSheet)
    {
        foreach($headers AS $key => $value){
            $activeSheet->SetCellValue(chr($key+65).'1',$value);
            $activeSheet->getStyle(chr($key+65).'1')->getFont()->setBold(true);
            $activeSheet->getStyle(chr($key+65).'1')->applyFromArray(array(
                'fill' => array(
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'E0E0E0')
                ),
                'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
            ));
        }
    }

    protected function drawData($data, $activeSheet)
    {
        $row = 2;
        foreach($data AS $line){
            $column = 0;
            foreach($line AS $value){
                $activeSheet->setCellValueByColumnAndRow($column, $row, $value);
                $column++;
            }
            $row++;
        }
    }

    protected function autoSize($activeSheet){
        for($col = 'A'; $col !== 'Z'; $col++) {
            $activeSheet
                ->getColumnDimension($col)
                ->setAutoSize(true);
        }
    }
}