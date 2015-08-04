<?php

namespace AppBundle\Composer;

use Composer\Script\CommandEvent;

/**
 */
class ScriptHandler
{
    /**
     * @param CommandEvent $event
     */
    public static function installDeployer(CommandEvent $event)
    {
        $deployerFile = getcwd().'/bin/deploy';

        if (!file_exists($deployerFile) && $event->isDevMode()) {
            $version = static::getOptions($event, 'deployer-version');
            $deployer = file_get_contents("http://deployer.org/releases/$version/deployer.phar");
            file_put_contents($deployerFile, $deployer);
        }
    }

    protected static function getOptions(CommandEvent $event, $option)
    {
        return $event->getComposer()->getPackage()->getExtra()[$option];
    }
}
