<?php

namespace Queo\Typo3\SoftwareCache\Request\Rule;

use Symfony\Component\HttpFoundation\Request;

interface RequestRule
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    public function validate(Request $request);
}