<?php


namespace Queo\Typo3\SoftwareCache\Request\Rule;

use Symfony\Component\HttpFoundation\Request;

class CombineAndRule implements RequestRule
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
        $isRequestRule = function ($rule) { return $rule instanceof RequestRule; };
        $this->rules = array_filter($rules, $isRequestRule);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request  $request
     *
     * @return bool
     */
    public function validate(Request $request)
    {
        /** @var RequestRule $rule */
        foreach ($this->rules as $rule)
        {
            if (!$rule->validate($request))
            {
                return false;
            }
        }

        return true;
    }
}