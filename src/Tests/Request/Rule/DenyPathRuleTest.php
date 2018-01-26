<?php
namespace Queo\Typo3\SoftwareCache\Tests\Request\Rule;

use Queo\Typo3\SoftwareCache\Request\Rule\DenyPathRule;
use Symfony\Component\HttpFoundation\Request;

class DenyPathRuleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test validation of cookie present
     */
    public function testCorrectValidation()
    {
        $rule = new DenyPathRule('login');

        $request = new Request();
        $request->server->add([
            'REQUEST_URI' => '/login-bereich.html'
        ]);

        $validationResult = $rule->validate($request);

        $this->assertTrue($validationResult);
    }

    /**
     * Test validation of cookie not present
     */
    public function testIncorrectValidation()
    {
        $rule = new DenyPathRule('login');

        /**
         * We add query string to simulate real world
         */
        $request = new Request();
        $request->server->add([
            'REQUEST_URI' => '/abc.html'
        ]);

        $validationResult = $rule->validate($request);

        $this->assertFalse($validationResult);
    }
}