<?php

namespace Aok\Typo3\SoftwareCache\Composer\Command\InstallCache;

class ConfigFactory
{
    /** @var array  */
    private static $options = [
        'typo3PhpLink' => 'typo3.php',
        'indexPhpLink' => 'index.php',
        'indexPhpTypo3SourcePath' => 'typo3_src/index.php',
        'softwareCacheIndexPhpPath' => 'index.dist.php',
    ];

    /**
     * @param array $settings
     *
     * @return \Aok\Typo3\SoftwareCache\Composer\Command\InstallCache\Config
     */
    public static function apply(array $settings)
    {
        $options = array_merge(
            static::$options,
            $settings
        );

        return new Config(
            $options['typo3PhpLink'],
            $options[ 'indexPhpLink'],
            $options['indexPhpTypo3SourcePath'],
            $options['softwareCacheIndexPhpPath']
        );
    }
}