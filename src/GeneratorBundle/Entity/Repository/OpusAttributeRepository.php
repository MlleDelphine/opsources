<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 27/08/2015
 * Time: 17:09
 */

namespace GeneratorBundle\Entity\Repository;


use GeneratorBundle\Entity\OpusSheet;
use Doctrine\ORM\EntityRepository;

class OpusAttributeRepository extends EntityRepository {

    public function getAttributesByCollectionType(OpusSheet $sheet, $fieldname){

        $array = array();

        $qb = $this->createQueryBuilder('a');
        $results = $qb
            ->join('a.collection', 'collection')
            ->join('collection.sheet', 'sheet')
            ->where("collection.type LIKE :fieldname")
            ->andWhere('sheet.id = :sheetID')
            ->setParameters(array('sheetID' => $sheet->getId(), 'fieldname' => '%'.$fieldname.'%'))
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();

        $i = 0;
        $j = 0;
        foreach (array_values($results) as $k => $result) {
            if($i%4 != 0) {
                $array[$j][] = array('label' => $result->getLabel(), 'value' => $result->getValue());
            }
            else{
                $j++;
                $array[$j][] = array('label' => $result->getLabel(), 'value' => $result->getValue());
            }
            $i++;
        }

        return $array;

    }

}