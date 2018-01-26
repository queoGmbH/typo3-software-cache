<?php

namespace Queo\Typo3\SoftwareCache\Proxy;

use Queo\Typo3\SoftwareCache\Cache\IdGenerator;
use Queo\Typo3\SoftwareCache\Cache\Rule\CacheRuleCollection;
use Queo\Typo3\SoftwareCache\Request\Rule\RequestRuleCollection;
use Doctrine\Common\Cache\Cache;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Proxy
{
    /**
     * @var \Doctrine\Common\Cache\Cache
     */
    private $cache;

    /**
     * @var \Queo\Typo3\SoftwareCache\Request\Rule\RequestRuleCollection
     */
    private $requestRuleCollection;

    /**
     * @var \Queo\Typo3\SoftwareCache\Cache\Rule\CacheRuleCollection
     */
    private $cacheRuleCollection;

    /**
     * @var \Queo\Typo3\SoftwareCache\Cache\IdGenerator
     */
    private $cacheIdGenerator;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @param \Doctrine\Common\Cache\Cache               $cache
     * @param \Queo\Typo3\SoftwareCache\Cache\IdGenerator $cacheIdGenerator
     * @param \Psr\Log\LoggerInterface                   $logger
     */
    public function __construct(Cache $cache, IdGenerator $cacheIdGenerator, LoggerInterface $logger)
    {
        $this->cache                 = $cache;
        $this->requestRuleCollection = new RequestRuleCollection;
        $this->cacheRuleCollection   = new CacheRuleCollection();
        $this->cacheIdGenerator      = $cacheIdGenerator;
        $this->logger                = $logger;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function handleRequest(Request $request)
    {
        $logData = [
            'SERVER' => [
                "SCRIPT_URL"         => $_SERVER["SCRIPT_URL"],
                "SCRIPT_URI"         => $_SERVER["SCRIPT_URI"],
                "QUERY_STRING"       => $_SERVER["QUERY_STRING"],
                "REQUEST_URI"        => $_SERVER["REQUEST_URI"],
                "SCRIPT_NAME"        => $_SERVER["SCRIPT_NAME"],
                "REQUEST_TIME_FLOAT" => $_SERVER["REQUEST_TIME_FLOAT"],
                "REQUEST_TIME"       => $_SERVER["REQUEST_TIME"],
            ],
        ];

        $this->logger->info('request', $logData);
        if (!$this->canHandleRequest($request))
        {
            return;
        }

        $cacheId = $this->cacheIdGenerator->generate($request);
        if ($this->cache->contains($cacheId))
        {
            $this->logger->info('Read Response from Cache!', ['CacheId' => $cacheId]);
            $this->dispatchCache($cacheId);
            exit;
        }
    }

    public function cacheResponse(Request $request, Response $response)
    {
        $cacheId = $this->cacheIdGenerator->generate($request);

        if ($this->cache->contains($cacheId) || !$this->canCacheResponse($request, $response))
        {
            return;
        }
        $this->logger->info('Save Response in Cache', ['CacheId' => $cacheId]);
        $response->headers->set('SoftwareCached', new \DateTime);

        $this->cache->save($cacheId, serialize($response), 900);
    }

    public function addRequestRules(array $rules)
    {
        $this->requestRuleCollection->addRules($rules);
    }

    public function addCacheRules(array $rules)
    {
        $this->cacheRuleCollection->addRules($rules);
    }

    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    private function canHandleRequest(Request $request)
    {
        return $this->requestRuleCollection->validate($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return bool
     */
    private function canCacheResponse(Request $request, Response $response)
    {
        return $this->cacheRuleCollection->validate($request, $response);
    }

    /**
     * @param $key
     */
    private function dispatchCache($key)
    {
        /** @var Response $reponse */
        $reponse = unserialize($this->cache->fetch($key));
        $reponse->send();
    }

}