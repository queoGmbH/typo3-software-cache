<?php


namespace Queo\Typo3\SoftwareCache\Cache\Rule;

use Assert\Assertion;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpMethodRule implements CacheRule
{
    /**
     * @var array
     */
    private $method;

    /**
     * @param string $method
     */
    public function __construct($method)
    {
        Assertion::string($method);
        $this->method = strtoupper($method);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return bool
     */
    public function validate(Request $request, Response $response)
    {
        return $request->getMethod() === $this->method;
    }
}