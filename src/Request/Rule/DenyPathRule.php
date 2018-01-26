<?php

namespace Queo\Typo3\SoftwareCache\Request\Rule;

use Assert\Assertion;
use Symfony\Component\HttpFoundation\Request;

class DenyPathRule implements RequestRule
{
    /**
     * @var array
     */
    private $pathPart;

    /**
     * @param string $pathPart
     */
    public function __construct($pathPart)
    {
        Assertion::string($pathPart);
        $this->pathPart = $pathPart;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request  $request
     *
     * @return bool
     */
    public function validate(Request $request)
    {
        return (strpos($request->getPathInfo(), $this->pathPart) !== FALSE);
    }
}