<?php

namespace Queo\Typo3\SoftwareCache\Request\Rule;

use Assert\Assertion;
use Symfony\Component\HttpFoundation\Request;

class HttpMethodRule implements RequestRule
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
     *
     * @return bool
     */
    public function validate(Request $request)
    {
        return $request->getMethod() === $this->method;
    }
}