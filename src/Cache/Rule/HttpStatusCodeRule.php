<?php


namespace Aok\Typo3\SoftwareCache\Cache\Rule;

use Assert\Assertion;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpStatusCodeRule implements CacheRule
{
    /**
     * @var array
     */
    private $code;

    /**
     * @param integer $code
     */
    public function __construct($code)
    {
        Assertion::integer($code);
        $this->code = strtoupper($code);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return bool
     */
    public function validate(Request $request, Response $response)
    {
        return $response->getStatusCode() === $this->code;
    }
}