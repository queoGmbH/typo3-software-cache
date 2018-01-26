<?php

namespace Queo\Typo3\SoftwareCache\Cache;

use Symfony\Component\HttpFoundation\Request;

interface IdGenerator
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    public function generate(Request $request);
}