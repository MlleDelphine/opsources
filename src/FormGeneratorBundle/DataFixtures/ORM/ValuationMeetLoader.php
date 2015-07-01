<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 26/06/2015
 * Time: 14:10
 */

namespace FormGeneratorBundle\DataFixtures\ORM;

use Hautelook\AliceBundle\Alice\DataFixtureLoader;
use Nelmio\Alice\Fixtures;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;


class ValuationMeetLoader extends DataFixtureLoader
{
    /**
     * {@inheritDoc}
     */
    protected function getFixtures()
    {
        return  array(
            __DIR__ . '/fixtures.yml',

        );
    }

}