<?php

namespace SEO_Crawler\Tests\Unit\Crawl;

use SEO_Crawler\Crawl\Crawler;
use SEO_Crawler\Url\SeoCrawlerUrl;
use SEO_Crawler\Exceptions\CrawlException;
use SEO_Crawler\Exceptions\InvalidParameterTypeException;
use WPMedia\PHPUnit\Unit\TestCase;
use Brain\Monkey\Functions;
use Mockery;
use ReflectionClass;

class CrawlerTest extends TestCase {
    /**
     * SEO Crawler instance
     *
     * @var SEO_Crawler\Crawl\Crawler
     */
    private $seo_crawler;

    /**
     * DB Mockery
     *
     * @var Mockery
     */
    private $mock_crawler_db;

    /**
     * FileSystem Mocked.
     *
     * @var Mockery
     */
    private $file_system_mock;

    /**
     * Setup for each test.
     * Initializes the mock objects and the class being tested.
     * 
     * @return void
     */
    public function setUp(): void {
        parent::setUp();

        // Mock the get_home_url() function
        Functions\when('get_home_url')->justReturn('http://127.0.0.1');
        Functions\when('esc_html')->alias(function($text) {
            return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        });
        $this->mock_crawler_db = Mockery::mock('overload:SEO_Crawler\Db\DbTable');
        // Create mock for the FileSystem class.
        $this->file_system_mock = Mockery::mock('overload:SEO_Crawler\Utils\FileSystem');
        $this->file_system_mock->shouldReceive('delete_file')->andReturnNull();
        $this->file_system_mock->shouldReceive('create_file')->andReturn(true);
        $this->seo_crawler = new Crawler($this->file_system_mock);

        // Use reflection to set the crawler_db property to the mock object
        $reflection = new ReflectionClass($this->seo_crawler);
        $property = $reflection->getProperty('crawler_db');
        $property->setAccessible(true);
        $property->setValue($this->seo_crawler, $this->mock_crawler_db);

    }

    public function tearDown(): void {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Tests that the getLatestResults method returns the expected results.
     *
     * @return void
     */
    public function testGetLatestResults() {
        $expectedResults = [
            new SeoCrawlerUrl('http://127.0.0.1', new \DateTime('2023-06-13 06:30:00')),
            new SeoCrawlerUrl('http://127.0.0.1/hello-wp-media', new \DateTime('2023-06-13 06:30:00'))
        ];

        // Mock the fetch_all method
        $this->mock_crawler_db->shouldReceive('fetch_all')
            ->andReturn($expectedResults);

        $this->assertEquals($expectedResults, $this->seo_crawler->getLatestResults());
    }

    /**
     * Tests that the getLatestResults method returns an empty array when no results are available.
     *
     * @return void
     */
    public function testGetLatestResultsWhenEmpty() {
        // Have the mock return an empty array
        $this->mock_crawler_db->shouldReceive('fetch_all')
            ->andReturn([]);

        // We expect the result to be an empty array
        $this->assertEquals([], $this->seo_crawler->getLatestResults());
    }

    /**
     * Tests the executeCrawl method to ensure it correctly performs a crawl and handles the results.
     *
     * @return void
     */
    public function testExecuteCrawl() {
        // Define the response from the wp_remote_get call.
        $wpRemoteGetResponse = [
            'headers' => [],
            'body' => '<html><body><a href="http://127.0.0.1/page1">Link1</a><a href="http://127.0.0.1/page2">Link2</a></body></html>',
            'response' => [
                'code' => 200,
                'message' => 'OK'
            ],
            'cookies' => [],
            'filename' => null
        ];

        // Mock the wp_remote_* functions.
        Functions\expect('wp_remote_get')
            ->with('http://127.0.0.1')
            ->andReturn($wpRemoteGetResponse);
        Functions\expect('wp_remote_retrieve_response_code')
            ->with($wpRemoteGetResponse)
            ->andReturn($wpRemoteGetResponse['response']['code']);
        Functions\expect('wp_remote_retrieve_body')
            ->with($wpRemoteGetResponse)
            ->andReturn($wpRemoteGetResponse['body']);


        // Create mock for the Crawler class with specific methods to mock
        $crawlerMock = Mockery::mock('SEO_Crawler\Crawl\Crawler', [$this->file_system_mock])->makePartial()
            ->shouldAllowMockingProtectedMethods();
    
        // Define the expected result of the crawlInternalLinks method
        $expectedLinks = ['http://127.0.0.1/page1', 'http://127.0.0.1/page2'];
    
        // Define the expectations for each method call
        $crawlerMock->shouldReceive('deletePreviousResults')->once()->andReturn(true);
        $crawlerMock->shouldReceive('crawlInternalLinks')->with('http://127.0.0.1')->andReturn($expectedLinks);
        $crawlerMock->shouldReceive('storeResults')->once()->with($expectedLinks)->andReturn(true);
        $crawlerMock->shouldReceive('saveHomePageAsHtml')->once()->andReturn(true);
        $crawlerMock->shouldReceive('deleteSitemapFile')->once()->andReturnNull();
        $crawlerMock->shouldReceive('createSitemapFile')->once()->with($expectedLinks)->andReturn(true);
    
        // Call the method under test
        $crawlerMock->executeCrawl();
    }

    /**
     * Tests the executeCrawl method with invalid parameters to ensure it correctly throws an exception.
     *
     * @return void
     */
    public function testExecuteCrawlInvalidParameter() {
        // Define the response from the wp_remote_get call.
        $wpRemoteGetResponse = [
            'headers' => [],
            'body' => '<html><body><a href="http://127.0.0.1/page1">Link1</a><a href="http://127.0.0.1/page2">Link2</a></body></html>',
            'response' => [
                'code' => 200,
                'message' => 'OK'
            ],
            'cookies' => [],
            'filename' => null
        ];

        // Mock the wp_remote_* functions.
        Functions\expect('wp_remote_get')
            ->with('http://127.0.0.1')
            ->andReturn($wpRemoteGetResponse);
        Functions\expect('wp_remote_retrieve_response_code')
            ->with($wpRemoteGetResponse)
            ->andReturn($wpRemoteGetResponse['response']['code']);
        Functions\expect('wp_remote_retrieve_body')
            ->with($wpRemoteGetResponse)
            ->andReturn($wpRemoteGetResponse['body']);

        // Create mock for the Crawler class with specific methods to mock
        $crawlerMock = Mockery::mock('SEO_Crawler\Crawl\Crawler')->makePartial()
            ->shouldAllowMockingProtectedMethods();
    
        // Define the expected result of the crawlInternalLinks method
        $expectedLinks = ['http://127.0.0.1/page1', 'http://127.0.0.1/page2'];
    
        // Define the expectations for each method call
        $crawlerMock->shouldReceive('deletePreviousResults')->andReturn(true);
        $crawlerMock->shouldReceive('crawlInternalLinks')->with('http://127.0.0.1')->andReturn($expectedLinks);
        $crawlerMock->shouldReceive('storeResults')->andThrow(InvalidParameterTypeException::class);
        $crawlerMock->shouldReceive('saveHomePageAsHtml')->never();
        $crawlerMock->shouldReceive('deleteSitemapFile')->never();
        $crawlerMock->shouldReceive('createSitemapFile')->never();
    
        $this->expectException(InvalidParameterTypeException::class);
        // Call the method under test
        $crawlerMock->executeCrawl();
    }

    /**
     * Tests the executeCrawl method when the body of the crawled response is empty.
     *
     * @return void
     */
    public function testExecuteCrawlEmptyBodyResponse() 
    {
        // Define the response from the wp_remote_get call.
        $wpRemoteGetResponse = [
            'headers' => [],
            'body' => '<html><body></body></html>',
            'response' => [
                'code' => 200,
                'message' => 'OK'
            ],
            'cookies' => [],
            'filename' => null
        ];

        // Mock the wp_remote_* functions.
        Functions\expect('wp_remote_get')
            ->with('http://127.0.0.1')
            ->andReturn($wpRemoteGetResponse);
        Functions\expect('wp_remote_retrieve_response_code')
            ->with($wpRemoteGetResponse)
            ->andReturn($wpRemoteGetResponse['response']['code']);
        Functions\expect('wp_remote_retrieve_body')
            ->with($wpRemoteGetResponse)
            ->andReturn($wpRemoteGetResponse['body']);

        // Create mock for the Crawler class with specific methods to mock
        $crawlerMock = Mockery::mock('SEO_Crawler\Crawl\Crawler', [$this->file_system_mock])->makePartial()
            ->shouldAllowMockingProtectedMethods();
    
        // Define the expected result of the crawlInternalLinks method
        $expectedLinks = [];
    
        // Define the expectations for each method call
        $crawlerMock->shouldReceive('deletePreviousResults')->once()->andReturn(true);
        $crawlerMock->shouldReceive('crawlInternalLinks')->with('http://127.0.0.1')->andReturn($expectedLinks);
        $crawlerMock->shouldReceive('storeResults')->once()->with($expectedLinks)->andReturn(true);
        $crawlerMock->shouldReceive('saveHomePageAsHtml')->once()->andReturn(true);
        $crawlerMock->shouldReceive('deleteSitemapFile')->once()->andReturnNull();
        $crawlerMock->shouldReceive('createSitemapFile')->once()->with($expectedLinks)->andReturn(true);
    
        // Call the method under test
        $crawlerMock->executeCrawl();
    }

    /**
     * Tests the executeCrawl method when a non-authorized (403) response is received.
     *
     * @return void
     */
    public function testExecuteCrawlNonAuthorized() {
        // Define the response from the wp_remote_get call.
        $wpRemoteGetResponse = [
            'headers' => [],
            'body' => '',
            'response' => [
                'code' => 403,
                'message' => 'Forbidden'
            ],
            'cookies' => [],
            'filename' => null
        ];

        // Mock the wp_remote_* functions.
        Functions\expect('wp_remote_get')
            ->with('http://127.0.0.1')
            ->andReturn($wpRemoteGetResponse);
        Functions\expect('wp_remote_retrieve_response_code')
            ->with($wpRemoteGetResponse)
            ->andReturn($wpRemoteGetResponse['response']['code']);
        Functions\expect('wp_remote_retrieve_body')
            ->with($wpRemoteGetResponse)
            ->andReturn($wpRemoteGetResponse['body']);


        // Create mock for the Crawler class with specific methods to mock
        $crawlerMock = Mockery::mock('SEO_Crawler\Crawl\Crawler')->makePartial()
            ->shouldAllowMockingProtectedMethods();
    
        // Define the expected result of the crawlInternalLinks method
        $expectedLinks = ['http://127.0.0.1/page1', 'http://127.0.0.1/page2'];
    
        // Define the expectations for each method call
        $crawlerMock->shouldReceive('deletePreviousResults')->once()->andReturn(true);
        $crawlerMock->shouldReceive('crawlInternalLinks')->with('http://127.0.0.1')->andThrow(CrawlException::class);
        $crawlerMock->shouldReceive('storeResults')->never();
        $crawlerMock->shouldReceive('saveHomePageAsHtml')->never();
        $crawlerMock->shouldReceive('deleteSitemapFile')->never();
        $crawlerMock->shouldReceive('createSitemapFile')->never();

    
        // Call the method under test
        $this->expectException(CrawlException::class);

        $crawlerMock->executeCrawl();
    }

    /**
     * Test case for the constructor of the Crawler class.
     * 
     * @return void
     */
    public function testConstructor() {
        $crawler = new Crawler();

        $this->assertInstanceOf(Crawler::class, $crawler);
    }
}
