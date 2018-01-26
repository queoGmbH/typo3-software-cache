<?php


namespace Queo\Typo3\SoftwareCache\Cache;

use Symfony\Component\HttpFoundation\Request;

class UriIdGenerator implements IdGenerator
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    public function generate(Request $request)
    {
        return $request->getHttpHost() . $request->getUri();
    }
}