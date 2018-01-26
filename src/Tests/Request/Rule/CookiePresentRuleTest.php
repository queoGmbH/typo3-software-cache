<?php

/**
 * Created by PhpStorm.
 * User: michael
 * Date: 09.05.2017
 * Time: 17:37
 */
namespace Queo\Typo3\SoftwareCache\Tests\Request\Rule;

use Queo\Typo3\SoftwareCache\Request\Rule\CookiePresentRule;
use Symfony\Component\HttpFoundation\Request;

class CookiePresentRuleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test validation of cookie present
     */
    public function testCorrectValidation()
    {
        $rule = new CookiePresentRule('test');

        $request = new Request();
        $request->cookies->add([
            'test' => 'testValue'
        ]);

        $validationResult = $rule->validate($request);

        $this->assertTrue($validationResult);
    }

    /**
     * Test validation of cookie not present
     */
    public function testIncorrectValidation()
    {
        $rule = new CookiePresentRule('notPresent');

        /**
         * We add some cookies to simulate real world
         */
        $request = new Request();
        $request->cookies->add([
            'test' => 'testValue'
        ]);

        $validationResult = $rule->validate($request);

        $this->assertFalse($validationResult);
    }
}