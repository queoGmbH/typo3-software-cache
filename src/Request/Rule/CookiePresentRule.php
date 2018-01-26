<?php

namespace Queo\Typo3\SoftwareCache\Request\Rule;

use Assert\Assertion;
use Symfony\Component\HttpFoundation\Request;

class CookiePresentRule implements RequestRule
{
    /**
     * @var array
     */
    private $cookieName;

    /**
     * @param string $cookieName
     */
    public function __construct($cookieName)
    {
        Assertion::string($cookieName);
        $this->cookieName = $cookieName;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request  $request
     *
     * @return bool
     */
    public function validate(Request $request)
    {
        return !is_null($request->cookies->get($this->cookieName));
    }
}