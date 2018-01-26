<?php

namespace Aok\Typo3\SoftwareCache\Composer\Command\InstallCache;

class Config
{
    private $typo3PhpLink = 'typo3.php';
    private $indexPhpLink = 'index.php';
    private $indexPhpTypo3SourcePath = 'typo3_src/indx.php';
    private $softwareCacheIndexPhpPath = 'index.dist.php';

    /**
     * Config constructor.
     *
     * @param string $typo3PhpLink
     * @param string $indexPhpLink
     * @param string $indexPhpTypo3SourcePath
     * @param string $softwareCacheIndexPhpPath
     */
    public function __construct($typo3PhpLink, $indexPhpLink, $indexPhpTypo3SourcePath, $softwareCacheIndexPhpPath)
    {
        $this->typo3PhpLink = $typo3PhpLink;
        $this->indexPhpLink = $indexPhpLink;
        $this->indexPhpTypo3SourcePath = $indexPhpTypo3SourcePath;
        $this->softwareCacheIndexPhpPath = $softwareCacheIndexPhpPath;
    }

    /**
     * @return string
     */
    public function getTypo3PhpLink()
    {
        return $this->typo3PhpLink;
    }

    /**
     * @return string
     */
    public function getIndexPhpLink()
    {
        return $this->indexPhpLink;
    }

    /**
     * @return string
     */
    public function getIndexPhpTypo3SourcePath()
    {
        return $this->indexPhpTypo3SourcePath;
    }

    /**
     * @return string
     */
    public function getSoftwareCacheIndexPhpPath()
    {
        return $this->softwareCacheIndexPhpPath;
    }
}