<?php

namespace Queo\Typo3\SoftwareCache\Tests\Proxy;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Queo\Typo3\SoftwareCache\Cache\UriIdGenerator;
use Queo\Typo3\SoftwareCache\Proxy\Proxy;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProxyTest extends TestCase
{
    /**
     * @var Proxy
     */
    protected $proxy;

    /**
     * @var ArrayAdapter
     */
    protected $cache;

    public function setUp()
    {
        $this->cache = new ArrayAdapter();

        $this->proxy = $this->getMockBuilder(Proxy::class)
            ->setConstructorArgs([
                $this->cache,
                new UriIdGenerator(),
                new NullLogger(),
            ])
            ->setMethods(['stop'])
            ->getMock();
    }

    public function testResponseCaching()
    {
        $request = Request::create('http://example.com');
        $response = Response::create('Hello');

        $this->proxy->cacheResponse($request, $response);

        $this->assertTrue($this->cache->hasItem('example.comhttpexample.com'));
    }

    public function testResponseHandling()
    {
        $request = Request::create('http://example.com');
        $response = Response::create('Hello');

        $cacheItem = $this->cache
            ->getItem('example.comhttpexample.com')
            ->set(serialize($response))
            ->expiresAfter(900);

        $this->cache->save($cacheItem);

        ob_start();

        $this->proxy->handleRequest($request);

        $output = ob_get_contents();

        ob_end_clean();

        $this->assertEquals('Hello', $output);
    }
}
