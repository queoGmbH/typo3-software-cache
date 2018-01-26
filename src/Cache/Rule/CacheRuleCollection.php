<?php


namespace Queo\Typo3\SoftwareCache\Cache\Rule;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheRuleCollection
{
    /**
     * @var array
     */
    private $cacheRules = [];

    /**
     * @param array $rules
     */
    public function addRules(array $rules)
    {
        $isCacheRule = function ($validator) { return $validator instanceof CacheRule; };

        foreach (array_filter($rules, $isCacheRule) as $cacheRule)
        {
            $this->cacheRules[] = $cacheRule;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return bool
     */
    public function validate(Request $request, Response $response)
    {
        /** @var CacheRule $cacheRule */
        foreach ($this->cacheRules as $cacheRule)
        {
            if (!$cacheRule->validate($request, $response))
            {
                return false;
            }
        }

        return true;
    }
}