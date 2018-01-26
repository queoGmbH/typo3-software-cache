<?php

namespace Queo\Typo3\SoftwareCache\Cache\Rule;

use Assert\Assertion;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DenyStringInContentRule implements CacheRule
{

    /**
     * @var array
     */
    private $string;

    /**
     * @param string $string
     */
    public function __construct($string)
    {
        Assertion::string($string);
        $this->string = $string;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return bool
     */
    public function validate(Request $request, Response $response)
    {
        return strpos($response->getContent(), $this->string) === FALSE;
    }
}