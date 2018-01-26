<?php


namespace Queo\Typo3\SoftwareCache\Cache\Rule;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CombineOrRule implements CacheRule
{
    /**
     * @var array
     */
    private $rules;

    /**
     * @param array $rules
     */
    public function __construct(array $rules)
    {
        $isCacheRule = function ($rule) { return $rule instanceof CacheRule; };
        $this->rules = array_filter($rules, $isCacheRule);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return bool
     */
    public function validate(Request $request, Response $response)
    {
        /** @var CacheRule $rule */
        foreach ($this->rules as $rule)
        {
            if ($rule->validate($request, $response))
            {
                return true;
            }
        }

        return false;
    }
}