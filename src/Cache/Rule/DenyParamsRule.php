<?php

namespace Queo\Typo3\SoftwareCache\Cache\Rule;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DenyParamsRule implements CacheRule
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return bool
     */
    public function validate(Request $request, Response $response)
    {
        return $request->query->count() === 0;
    }
}