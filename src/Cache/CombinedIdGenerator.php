<?php

namespace Queo\Typo3\SoftwareCache\Cache;

use Symfony\Component\HttpFoundation\Request;

class CombinedIdGenerator implements IdGenerator
{
    /**
     * @var IdGenerator[]
     */
    private $generators = [];

    /**
     * @param array $generators
     */
    public function __construct(array $generators)
    {
        $isIdGenerator = function ($generator) { return $generator instanceof IdGenerator; };
        $this->generators = array_filter($generators, $isIdGenerator);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    public function generate(Request $request)
    {
        $combinator = function ($carry, IdGenerator $generator) use ($request) {
            return $carry . $generator->generate($request);
        };

        return array_reduce($this->generators, $combinator, '');
    }

}