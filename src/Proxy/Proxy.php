<?php

namespace Queo\Typo3\SoftwareCache\Proxy;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Queo\Typo3\SoftwareCache\Cache\IdGenerator;
use Queo\Typo3\SoftwareCache\Cache\Rule\CacheRuleCollection;
use Queo\Typo3\SoftwareCache\Request\Rule\RequestRuleCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Proxy
{
    /**
     * @var int default cache lifetime in seconds
     */
    protected const DEFAULT_CACHE_LIFETIME = 900;

    /**
     * @var CacheItemPoolInterface
     */
    private $cache;

    /**
     * @var RequestRuleCollection
     */
    private $requestRuleCollection;

    /**
     * @var CacheRuleCollection
     */
    private $cacheRuleCollection;

    /**
     * @var array
     */
    protected $additionalHeaders = [];

    /**
     * @var array
     */
    protected $defaultHeaders = [];

    /**
     * @var int
     */
    protected $cacheLifetime;

    /**
     * @var IdGenerator
     */
    private $cacheIdGenerator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param CacheItemPoolInterface $cache
     * @param IdGenerator            $cacheIdGenerator
     * @param LoggerInterface        $logger
     */
    public function __construct(CacheItemPoolInterface $cache, IdGenerator $cacheIdGenerator, LoggerInterface $logger)
    {
        $this->cache                 = $cache;
        $this->requestRuleCollection = new RequestRuleCollection();
        $this->cacheRuleCollection   = new CacheRuleCollection();
        $this->cacheIdGenerator      = $cacheIdGenerator;
        $this->logger                = $logger;

        $this->defaultHeaders = [
            'X-Cache: HIT from QueoTypo3SoftwareCache' => (new \DateTime())->format("Y-m-d H:i:s")
        ];
    }

    /**
     * @param Request $request
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function handleRequest(Request $request)
    {
        if (!$this->canHandleRequest($request)) {
            return;
        }

        $cacheId = $this->cacheIdGenerator->generate($request);
        if ($this->cache->hasItem($cacheId)) {
            $this->logger->info('Read Response from Cache!', ['CacheId' => $cacheId]);
            $this->dispatchCache($cacheId);
            $this->stop();
        }
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function cacheResponse(Request $request, Response $response)
    {
        $cacheId = $this->cacheIdGenerator->generate($request);

        if ($this->cache->hasItem($cacheId) || !$this->canCacheResponse($request, $response)) {
            return;
        }

        $response = $this->buildHeaderData($response);

        $this->logger->info('Save Response in Cache', ['CacheId' => $cacheId]);
        $cacheItem = $this->cache
            ->getItem($cacheId)
            ->set(serialize($response))
            ->expiresAfter($this->getCacheLifetime());

        $this->cache->save($cacheItem);
    }

    /**
     * @param Response $response
     * @return Response
     */
    public function buildHeaderData(Response $response)
    {
        $headerData = array_merge($this->additionalHeaders, $this->defaultHeaders);

        foreach ($headerData as $headerKey => $headerValue) {
            $response->headers->set($headerKey, $headerValue);
        }
        return $response;
    }

    /**
     * @param array $additionalHeaders
     */
    public function addAdditionalHeaders($additionalHeaders)
    {
        $this->additionalHeaders = $additionalHeaders;
    }

    /**
     * @param array $rules
     */
    public function addRequestRules(array $rules)
    {
        $this->requestRuleCollection->addRules($rules);
    }

    /**
     * @param array $rules
     */
    public function addCacheRules(array $rules)
    {
        $this->cacheRuleCollection->addRules($rules);
    }

    /**
     *
     * @param Request $request
     *
     * @return bool
     */
    private function canHandleRequest(Request $request)
    {
        return $this->requestRuleCollection->validate($request);
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return bool
     */
    private function canCacheResponse(Request $request, Response $response)
    {
        return $this->cacheRuleCollection->validate($request, $response);
    }

    /**
     * @param $key
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    private function dispatchCache($key)
    {
        /** @var Response $response */
        $response = unserialize($this->cache->getItem($key)->get());
        $response->send();
    }

    /**
     * Stop execution
     */
    protected function stop()
    {
        exit;
    }

    /**
     * @param int $cacheLifetime
     */
    public function setCacheLifetime($cacheLifetime)
    {
        $this->cacheLifetime = $cacheLifetime ? : self::DEFAULT_CACHE_LIFETIME;
    }

    public function getCacheLifetime()
    {
        return $this->cacheLifetime ? : self::DEFAULT_CACHE_LIFETIME;
    }
}