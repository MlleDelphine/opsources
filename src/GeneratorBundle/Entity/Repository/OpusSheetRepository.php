<?php

namespace GeneratorBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use GeneratorBundle\Entity\OpusCampaign;
use Symfony\Component\Validator\Constraints\DateTime;
use UserBundle\Entity\User;

/**
 * OpusSheetTypeRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OpusSheetRepository extends EntityRepository
{
    /**
     * Retourne les fiches en cours d'édition pour une campagne et un utilisateur donnés.
     *
     * @param OpusCampaign $campaign
     * @param User         $user
     *
     * @return int
     */
    public function findSheetsNotClosedInCampaign(OpusCampaign $campaign, User $user)
    {
        $qb = $this->createQueryBuilder('sh');
        $result = $qb->join('sh.status', 's')
            ->join('sh.campaign', 'c')
            ->join('sh.evaluate', 'e')
            ->where($qb->expr()->notLike('s.strCode', 'close'))
            ->andWhere($qb->expr()->notLike('s.strCode', 'close_finish'))
            ->where('c.id = :campaignId')
            ->andWhere('e.id = :userId')
            ->setParameters(array(
                'campaignId' => $campaign->getId(),
                'userId' => $user->getId()))
            ->orderBy('sh.id', 'DESC')
            ->getQuery()
            ->setMaxResults(1)
            ->getResult();

        return $result;
    }

    public function findSheetsWithoutCampaign($user, $opusCampaign){
        $qb = $this->createQueryBuilder('opusSheet');
        $result = $qb
            ->join('opusSheet.evaluate','evaluate')
            ->join('opusSheet.evaluator','evaluator')
            ->join('opusSheet.opusTemplate','opusTemplate')
            ->join('opusTemplate.type','opusTemplateType')
            ->where('evaluate.id = :evaluate')
            ->andWhere('evaluator.id = :evaluator')
            ->andWhere($qb->expr()->isNull('opusSheet.campaign'))
            ->andWhere('opusTemplateType.id = :opusTemplateType')
            ->andWhere('opusSheet.createdAt > :dateSheet')
            ->setParameter('evaluate', $user->getId())
            ->setParameter('evaluator', $user->getManager()->getId())
            ->setParameter('opusTemplateType', $opusCampaign->getOpusTemplate()->getType()->getId())
            ->setParameter('dateSheet', $opusCampaign->getUntilSheetDate(), \Doctrine\DBAL\Types\Type::DATETIME)
            ->getQuery()->getResult();

        return $result;
    }

    /*
 * ToDo
 */
    public function getMobilityForExport(){
        $objects = $this->findBy(array(),array('id'=>'ASC'));
        $qb = $this->createQueryBuilder('opusSheet');
        $users = $qb
            ->select('evaluate.id')
            ->join('opusSheet.collections','collections')
            ->join('collections.attributes','attributes')
            ->join('opusSheet.evaluate','evaluate')
            ->where('attributes.label LIKE :label')
            ->andWhere('attributes.value IS NOT NULL')
            ->groupBy('evaluate.id')
            ->setParameter('label', 'improve_comment')
            ->getQuery()->getResult();
//        professional_mobility_bool

        $em = $this->getEntityManager();

        $objects = array();
        foreach($users AS $user){
            $evaluate = $em->getRepository('UserBundle:User')->find($user,array('lastName'=>'ASC'));
            array_push($objects,$evaluate);
        }

        $data = array();

        foreach($objects AS $value){
            $line = array($value->getLastname(),$value->getFirstname());
            array_push($data,$line);
        }

        $headers = array('Nom','Prenom');

        return array($headers,$data);
    }

    /*
 * ToDo
 */
    public function getStrongPointsForExport(){
        $objects = $this->findBy(array(),array('id'=>'ASC'));

        $data = array();

        foreach($objects AS $value){
            $line = array($value->getId(),$value->getFirstname(),$value->getLastname());
            array_push($data,$line);
        }

        $headers = array('ID','Firstname','Lastname');

        return array($headers,$data);
    }

    /*
 * ToDo
 */
    public function getUsersFunctionForExport(){
        $objects = $this->findBy(array(),array('id'=>'ASC'));
        $qb = $this->createQueryBuilder('opusSheet');
        $users = $qb
            ->select('attributes.value')
            ->join('opusSheet.collections','collections')
            ->join('collections.attributes','attributes')
            ->join('opusSheet.evaluate','evaluate')
            ->where('attributes.label LIKE :label')
//            ->andWhere('attributes.value IS NOT NULL')
            ->setParameter('label', 'current_function')
            ->getQuery()->getResult();
//        professional_mobility_bool

        $em = $this->getEntityManager();
        $objects = array();
        foreach($users AS $user){
            $evaluate = $em->getRepository('UserBundle:User')->find($user->getEvaluate(),array('lastName'=>'ASC'));
            array_push($objects,$evaluate);
        }

        $data = array();

        foreach($objects AS $value){
            dump($value);die();
            $line = array($value->getLastname(),$value->getFirstname());
            array_push($data,$line);
        }

        $headers = array('Nom','Prenom');

        return array($headers,$data);
    }

    /**
     * Retourne toutes les fiches où la valeur de l'attribut fieldName est égale à $value
     *
     * @param $fieldName
     * @param $value
     * @return array
     */
    public function findForAttributeExport($fieldName, $value){

        $formats = array("d.m.Y", "d/m/Y", "Ymd", "Y-m-d H:i:s");
        $isDate = false;

        foreach ($formats as $format)
        {
            $date = \DateTime::createFromFormat($format, $value);
            if ($date == false || !(date_format($date,$format) == $value) )
            {}
            else
            {
                $isDate = true;
                break;
            }
        }

        if($isDate && $value){
            $qb = $this->createQueryBuilder('opusSheet');
            $result = $qb
                ->join('opusSheet.attributes', 'attributes')
                ->where('attributes.label = :fieldName')
                ->andWhere('attributes.value = :value OR attributes.valueDate = :value')
                ->setParameters(array('fieldName' => $fieldName, 'value' => $value))
                ->getQuery()->getResult();
        }elseif(!$isDate && $value){
            $qb = $this->createQueryBuilder('opusSheet');
            $result = $qb
                ->join('opusSheet.attributes', 'attributes')
                ->where('attributes.label = :fieldName')
                ->andWhere('attributes.value = :value OR attributes.valueData = :value')
                ->setParameters(array('fieldName' => $fieldName, 'value' => $value))
                ->getQuery()->getResult();
        }
        else{
            $qb = $this->createQueryBuilder('opusSheet');
            $result = $qb
                ->join('opusSheet.attributes', 'attributes')
                ->where('attributes.label = :fieldName')
                ->getQuery()->getResult();
        }

        return $result;

    }


    public function getAllDates()
    {
        $qb = $this->createQueryBuilder('s');
        $results = $qb->orderBy('s.createdAt', 'DESC')
            ->getQuery()
            ->getResult();

        $dates = array();
        foreach ($results as $sheet) {
            $dates[$sheet->getCreatedAt()->format('Y')] = $sheet->getCreatedAt()->format('Y');
        }


        return $dates;
    }



}
