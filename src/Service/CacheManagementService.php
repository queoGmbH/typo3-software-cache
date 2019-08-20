<?php

namespace Queo\Typo3\SoftwareCache\Service;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class CacheManagementService
{
    /**
     * @var \Symfony\Component\Cache\Adapter\FilesystemAdapter
     */
    protected $cacheDriver;

    public function __construct()
    {
        $this->cacheDriver = new FilesystemAdapter();
    }

    public function clearCache()
    {
        return $this->cacheDriver->clear();
    }

    public function getCacheDriver() {
        return $this->cacheDriver();
    }
}