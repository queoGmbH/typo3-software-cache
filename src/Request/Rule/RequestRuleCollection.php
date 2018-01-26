<?php


namespace Queo\Typo3\SoftwareCache\Request\Rule;

use Symfony\Component\HttpFoundation\Request;

final class RequestRuleCollection
{
    /**
     * @var array
     */
    private $requestRules = [];

    /**
     * @param array $rules
     */
    public function addRules(array $rules)
    {
        $isRequestRules = function ($validator) { return $validator instanceof RequestRule; };

        foreach (array_filter($rules, $isRequestRules) as $requestRule) {
            $this->requestRules[] = $requestRule;
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    public function validate(Request $request)
    {
        /** @var RequestRule $requestRule */
        foreach ($this->requestRules as $requestRule)
        {
            if (!$requestRule->validate($request))
            {
                return false;
            }
        }

        return true;
    }
}