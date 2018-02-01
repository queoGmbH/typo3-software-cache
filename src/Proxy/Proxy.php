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
        $this->logger->info('Save Response in Cache', ['CacheId' => $cacheId]);
        $response->headers->set('X-Cache: HIT from QueoTypo3SoftwareCache', (new \DateTime())->format("Y-m-d H:i:s"));

        $cacheItem = $this->cache
            ->getItem($cacheId)
            ->set(serialize($response))
            ->expiresAfter(900);

        $this->cache->save($cacheItem);
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
        /** @var Response $reponse */
        $reponse = unserialize($this->cache->getItem($key)->get());
        $reponse->send();
    }

    /**
     * Stop execution
     */
    protected function stop()
    {
        exit;
    }
}