<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 25/08/2015
 * Time: 14:17
 */

namespace GeneratorBundle\Entity\Repository;


use Doctrine\ORM\EntityRepository;

class OpusCollectionRepository extends EntityRepository{


    /**
     * Retourne toutes les fiches où la valeur de l'attribut fieldName est égale à $value
     *
     * @param $fieldName
     * @param $value
     * @return array
     */
    public function findForCollectionExport($fieldName){

        $qb = $this->createQueryBuilder('collections');
        $result = $qb
            ->where('collections.type = :fieldName')
            // ->andWhere('attributes.value = :value OR attributes.valueDate = :value')
            ->setParameters(array('fieldName' => $fieldName))
            ->getQuery()->getResult();

        return $result;

    }

}