<?php

/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 26/06/2015
 * Time: 14:10.
 */

namespace FormGeneratorBundle\DataFixtures\ORM;

use Hautelook\AliceBundle\Alice\DataFixtureLoader;

class PrepopulateLoader extends DataFixtureLoader
{
    /**
     * {@inheritDoc}
     */
    protected function getFixtures()
    {
        $fix = [
            /*"GeneratorBundle:OpusSheetStatus" => __DIR__.'/OpusSheetStatus_Fixtures.yml',*/
            "GeneratorBundle:OpusSheetType" => __DIR__.'/OpusSheetType_Fixtures.yml'
        ];
        $return = [];
        foreach($fix as $repo => $file) {
            $repo = $this->container->get('doctrine')->getEntityManager()->getRepository($repo)->findAll();
            if (empty($repo))
                array_push($return, $file);
        }
        return $return;


    }
}
