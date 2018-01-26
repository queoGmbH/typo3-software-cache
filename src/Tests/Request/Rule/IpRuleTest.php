<?php

/**
 * Created by PhpStorm.
 * User: michael
 * Date: 09.05.2017
 * Time: 17:37
 */
namespace Queo\Typo3\SoftwareCache\Tests\Request\Rule;

use Queo\Typo3\SoftwareCache\Request\Rule\IpRule;
use Symfony\Component\HttpFoundation\Request;

class IpRuleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test validation of cookie present
     */
    public function testCorrectValidation()
    {
        $rule = new IpRule([
            '192.0.0.1'
        ]);

        $request = new Request();
        $request->server->add([
            'REMOTE_ADDR' => '192.0.0.1'
        ]);

        $validationResult = $rule->validate($request);

        $this->assertTrue($validationResult);
    }

    /**
     * Test validation of cookie not present
     */
    public function testIncorrectValidation()
    {
        $rule = new IpRule([
            '192.0.0.1'
        ]);

        $request = new Request();
        $request->server->add([
            'REMOTE_ADDR' => '192.0.0.2'
        ]);

        $validationResult = $rule->validate($request);

        $this->assertFalse($validationResult);
    }
}