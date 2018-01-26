<?php

/**
 * Created by PhpStorm.
 * User: michael
 * Date: 09.05.2017
 * Time: 17:37
 */
namespace Queo\Typo3\SoftwareCache\Tests\Request\Rule;

use Queo\Typo3\SoftwareCache\Cache\Rule\DenyStringInContentRule;
use Queo\Typo3\SoftwareCache\Request\Rule\CookiePresentRule;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DenyStringInContentRuleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test validation of cookie present
     */
    public function testCorrectValidation()
    {
        $rule = new DenyStringInContentRule('test');

        $request = new Request();
        $response = new Response();
        $response->setContent('Ninja Turtles');

        $validationResult = $rule->validate($request, $response);

        $this->assertTrue($validationResult);
    }

    /**
     * Test validation of cookie not present
     */
    public function testIncorrectValidation()
    {
        $rule = new DenyStringInContentRule('test');

        $request = new Request();
        $response = new Response();
        $response->setContent('test');

        $validationResult = $rule->validate($request, $response);

        $this->assertFalse($validationResult);
    }
}