<?php

namespace SEO_Crawler\Tests\Unit\Utils;

use SEO_Crawler\Exceptions\InvalidParameterTypeException;
use SEO_Crawler\Utils\Common;
use PHPUnit\Framework\TestCase;
use Brain\Monkey\Functions;

/**
 * Class CommonTest
 *
 * Test cases for the Common class.
 */
class CommonTest extends TestCase {
    /**
     * Set up the test environment.
     *
     * @return void
     */
    public function setUp(): void {
        Functions\when('get_home_url')->justReturn('https://127.0.0.1');
    }

    /**
     * Test is_internal_link method with an internal link.
     *
     * @return void
     */
    public function testIsInternalLinkWithInternalLink() {
        $url = 'https://127.0.0.1/about';
        $this->assertTrue(Common::is_internal_link($url));
    }

    /**
     * Test is_internal_link method with an external link.
     *
     * @return void
     */
    public function testIsInternalLinkWithExternalLink() {
        $url = 'https://google.com';
        $this->assertFalse(Common::is_internal_link($url));
    }

    /**
     * Test is_internal_link method with an invalid parameter type.
     *
     * @return void
     */
    public function testIsInternalLinkWithInvalidParameterType() {
        $this->expectException(InvalidParameterTypeException::class);
        $url = 12345; // Invalid parameter type, expected string
        Common::is_internal_link($url);
    }
}
