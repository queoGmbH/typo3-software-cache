<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 16.06.2017
 * Time: 15:46
 */

namespace Queo\Typo3\SoftwareCache\Tests\Cache;

use Queo\Typo3\SoftwareCache\Cache\LocalisationCookieIdGenerator;
use Symfony\Component\HttpFoundation\Request;

class LocalisationCookieIdGeneratorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test generation of cookie id
     */
    public function testKeyGenerator()
    {
        $localisationCookieIdGenerator = new LocalisationCookieIdGenerator();

        $request = new Request();
        $request->cookies->add([
            'localisation' => '{"client":{"11":"AOK Bayern"},"location":null}'
        ]);

        $id = $localisationCookieIdGenerator->generate($request);
        $this->assertEquals('null_11', $id);
    }

    /**
     * Test generation of cookie id when no cookie is set
     */
    public function testKeyGeneratorWithNoCookie()
    {
        $localisationCookieIdGenerator = new LocalisationCookieIdGenerator();

        $request = new Request();

        $id = $localisationCookieIdGenerator->generate($request);
        $this->assertEquals('', $id);
    }
}