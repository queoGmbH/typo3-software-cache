<?php

namespace Aok\Typo3\SoftwareCache\Composer\Command\InstallCache;

use Symfony\Component\Filesystem\Filesystem;

class Command
{
    /**
     * @var \Aok\Typo3\SoftwareCache\Composer\Command\InstallCache\Config
     */
    private $config;
    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    private $filesystem;

    /**
     * @param \Aok\Typo3\SoftwareCache\Composer\Command\InstallCache\Config $config
     * @param \Symfony\Component\Filesystem\Filesystem                      $filesystem
     */
    public function __construct(Config $config, Filesystem $filesystem)
    {
        $this->config = $config;
        $this->filesystem = $filesystem;
    }

    public function execute()
    {
        $docRoot = __DIR__ . '/../../../../../../../';

        $this->filesystem->remove($this->config->getIndexPhpLink());
        $this->filesystem->hardlink(
            $docRoot . $this->config->getIndexPhpTypo3SourcePath(),
            $docRoot . $this->config->getTypo3PhpLink()
        );

        $this->filesystem->remove($this->config->getIndexPhpLink());
        $this->filesystem->hardlink(
            $docRoot . $this->config->getSoftwareCacheIndexPhpPath(),
            $docRoot . $this->config->getIndexPhpLink()
        );
    }
}