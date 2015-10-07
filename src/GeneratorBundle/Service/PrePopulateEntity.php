<?php

/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 12/06/2015
 * Time: 10:49.
 */

namespace GeneratorBundle\Service;

use GeneratorBundle\Entity\OpusAttribute;
use GeneratorBundle\Entity\OpusCollection;
use GeneratorBundle\Entity\OpusSheet;
use GeneratorBundle\Form\Sheets\OpusSheetType;
use Symfony\Component\Form\FormFactory;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\Form;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class PrePopulateEntity
{
    private $formFactory;
    private $router;
    private $em;
    private $security;
    private $accessControl;

    public function __construct(FormFactory $formFactory, Router $router, EntityManager $em, TokenStorage $security, AccessControlSheet $accessControl)
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->em = $em;
        $this->security = $security;
        $this->accessControl = $accessControl;
    }

    public function populateOpusSheet(OpusSheet $sheet, $attributes, $update = false)
    {

        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
        //Pour objectifs
        $okey = 1;
        $lastSheet = null;
        $lastAttributes = null;
        if($update == false) {
            foreach (array_reverse($attributes['attr']) as $allConf) {
                $attr = new OpusAttribute();
                $attr->setLabel($allConf['id']);
                $sheet->addAttribute($attr);
            }
            if (array_key_exists('collections', $attributes)) {
                foreach ($attributes['collections'] as $collection) {
                    $number = 5;
                    if (array_key_exists('number', $collection)) {
                        $number = $collection['number'];
                    }
                    for ($i = 0; $i < $number; ++$i ) {
                        $opusCollection = new OpusCollection();
                        $opusCollection->setType($collection['id']);
                        $opusCollection->setLocation($i + 1);

                        $sheet->addCollection($opusCollection);
                        //On reverse car doctrine persist à l'envers : règle le souci des collections dans le mauvais ordre + des attributs des collections
                        foreach (array_reverse($collection['child']) as $child) {
                            $opusAttribute = new OpusAttribute();
                            $opusAttribute->setLabel($child['id']);
                            if (array_key_exists('predefined_values', $collection)){
                                if(array_key_exists($child['id'], $collection['predefined_values'])) {
                                    $reversePredefined = array_reverse($collection['predefined_values'][$child['id']]);
                                    $opusAttribute->setValue($reversePredefined[$i]);
                                }
                            }

                            //Pour les objectifs, s'il y a une ancienne fiche et l'attribut evaluation_achievement_objectives_rappel dans celle qu'on souhaite créer (rappel obj)
                            if($collection['id'] == 'evaluation_achievement_objectives'){
                                $lastSheet = $this->em->getRepository("GeneratorBundle:OpusSheet")->getLastSheetForEvaluateForObjectives($sheet->getEvaluate(), $sheet->getOpusTemplate()->getType());
                                if($lastSheet && $lastSheet->getId() != $sheet->getId()){
                                    $lastAttributes = $this->em->getRepository("GeneratorBundle:OpusAttribute")->getAttributesByCollectionType($lastSheet, 'new_objectif');
                                    if($child['id'] == 'evaluation_achievement_objectives_rappel'){
                                        $key = array_search('objectif', array_column($lastAttributes[$okey], 'label'));
                                        $opusAttribute->setValue($lastAttributes[$okey][$key]['value']);

                                    }elseif($child['id'] == 'evaluation_achievement_objectives_measurement_indicator'){
                                        $key = array_search('mesure', array_column($lastAttributes[$okey], 'label'));
                                        $opusAttribute->setValue($lastAttributes[$okey][$key]['value']);
                                    }
                                    elseif($child['id'] == 'evaluation_achievement_objectives_maturities'){
                                        $key = array_search('echeance', array_column($lastAttributes[$okey], 'label'));
                                        $opusAttribute->setValue($lastAttributes[$okey][$key]['value']);
                                    }
                                }
                            }
                            $opusCollection->addAttribute($opusAttribute);
                        }

                        if($collection['id'] == 'evaluation_achievement_objectives'){
                            $okey++;
                        }
                    }
                }
            }

            $this->em->persist($sheet);
            $this->em->flush();
            $this->em->clear($sheet);
            if($lastSheet){
                $this->em->clear($lastSheet);
            }
            if($lastAttributes) {
                $this->em->clear($lastAttributes);
            }


            return true;
        }

        return $this->createOpusSheetCreateForm($sheet, $attributes);
    }


    /**
     * OpusSheet création du formulaire de création.
     *
     * @param OpusSheet $entity
     * @param $attributes
     *
     * @return Form|\Symfony\Component\Form\FormInterface
     */
    public function createOpusSheetCreateForm(OpusSheet $entity, $attributes)
    {
        $form = $this->formFactory->create(
            new OpusSheetType($attributes, $this->em, $this->security, $this->accessControl),
            $entity,
            array(
                'action' => $this->router->generate('generator_updatesheet', array('id' => $entity->getId())),
                'method' => 'POST',
            )
        );

        return $form;
    }

    /**
     * Associe la clé parent à l'id du champ du fichier de conf .yml.
     *
     * @param $array
     *
     * @return array
     */
    private function associateKeyId($array)
    {
        $result = array();
        foreach ($array as $k => $v) {
            $result[$k] = $v['id'];
        }

        return $result;
    }

    /**
     * Associe la clé parent à l'id du champ du fichier de conf .yml.
     *
     * @param $array
     *
     * @return array*
     */
    private function existingNameField($attr)
    {
        $result = array();
        foreach ($attr as $k => $v) {
            $result[$k] = $v->getName();
        }

        return $result;
    }
}
