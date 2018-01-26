<?php

/**
 * Created by PhpStorm.
 * User: michael
 * Date: 09.05.2017
 * Time: 17:37
 */

namespace Queo\Typo3\SoftwareCache\Tests\Request\Rule;

use Queo\Typo3\SoftwareCache\Request\Rule\HttpMethodRule;
use Symfony\Component\HttpFoundation\Request;

class HttpMethodRuleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test validation of cookie present
     */
    public function testCorrectValidation()
    {
        $rule = new HttpMethodRule('get');

        $request = new Request();
        $request->server->add([
            'REQUEST_METHOD' => 'GET'
        ]);

        $validationResult = $rule->validate($request);

        $this->assertTrue($validationResult);
    }

    /**
     * Test validation of cookie not present
     */
    public function testIncorrectValidation()
    {
        $rule = new HttpMethodRule('get');

        $request = new Request();
        $request->server->add([
            'REQUEST_METHOD' => 'POST'
        ]);

        $validationResult = $rule->validate($request);

        $this->assertFalse($validationResult);
    }
}