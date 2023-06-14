<?php

namespace SEO_Crawler\Tests\Unit\Url;

use PHPUnit\Framework\TestCase;
use SEO_Crawler\Url\SeoCrawlerUrl;
use SEO_Crawler\Exceptions\InvalidParameterTypeException;
use Brain\Monkey\Functions;

/**
 * Class SeoCrawlerUrlTest
 */
class SeoCrawlerUrlTest extends TestCase {

    /**
     * Set up the test case.
     * 
     * @return void
     */
    public function setUp(): void {
        Functions\when('esc_html')->alias(function($text) {
            return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        });
    }
    
    /**
     * Test constructor with correct parameters.
     * 
     * @return void
     */
    public function testConstructordWithCorrectParameters() {
        $url = new SeoCrawlerUrl('https://example.com', '2023-06-13');

        $this->assertInstanceOf(SeoCrawlerUrl::class, $url);
    }

    /**
     * Test constructor throws an exception when URL is not a string.
     * 
     * @return void
     */
    public function testConstructorThrowsExceptionWhenUrlIsNotString() {
        $this->expectException(InvalidParameterTypeException::class);

        new SeoCrawlerUrl(123, '2023-06-13');
    }

    /**
     * Test constructor throws an exception when the date is a random string.
     * 
     * @return void
     */
    public function testConstructorThrowsExceptionWhenDateIsRandomString() {
        $this->expectException(InvalidParameterTypeException::class);

        new SeoCrawlerUrl('http://example.com', 'thisisrandom');
    }

    /**
     * Test constructor throws an exception when the date is not a string or DateTime object.
     * 
     * @return void
     */
    public function testConstructorThrowsExceptionWhenDateIsNotStringOrDateTime() {
        $this->expectException(InvalidParameterTypeException::class);

        new SeoCrawlerUrl('https://example.com', 123);
    }

     /**
     * Test getUrl() returns the correct URL.
     * 
     * @return void
     */
    public function testGetUrlReturnsCorrectUrl() {
        $url = new SeoCrawlerUrl('https://example.com', '2023-06-13');

        $this->assertEquals('https://example.com', $url->get_url());
    }

    /**
     * Test getCreationDate() returns the correct date.
     * 
     * @return void
     */
    public function testGetCreationDateReturnsCorrectDate() {
        $url = new SeoCrawlerUrl('https://example.com', '2023-06-13');

        $this->assertEquals(new \DateTime('2023-06-13'), $url->get_creation_date());
    }
}
