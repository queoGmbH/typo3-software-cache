<?php

namespace Aok\Typo3\SoftwareCache\Composer;

use Aok\Typo3\SoftwareCache\Composer\Command\InstallCache\ConfigFactory as InstallCacheConfigFactory;
use Aok\Typo3\SoftwareCache\Composer\Command\InstallCache\Command as InstallCacheCommand;
use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;

class ScriptHandler
{
    public static function installCacheCommand(Event $event)
    {
        $config  = InstallCacheConfigFactory::apply($event->getComposer()->getPackage()->getExtra());
        $command = new InstallCacheCommand($config, new Filesystem);

        $command->execute();
    }
}