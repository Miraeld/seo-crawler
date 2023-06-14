<?php

namespace SEO_Crawler\Tests\Unit\Utils;

use PHPUnit\Framework\TestCase;
use SEO_Crawler\Utils\Loader;
use Brain\Monkey\Functions;

/**
 * Class LoaderTest
 *
 * Test cases for the Loader class.
 */
class LoaderTest extends TestCase {
    /**
     * Test adding an action to the collection.
     *
     * @return void
     */
    public function testAddAction() {
        $loader = new Loader();
        $loader->add_action('wp_head', 'my_function', 10, 2);
        $loader->run();

        $this->assertGreaterThan(0, has_action('wp_head', 'my_function'));
    }

    /**
     * Test adding a filter to the collection.
     *
     * @return void
     */
    public function testAddFilter() {
        $loader = new Loader();
        $loader->add_filter('the_content', 'my_function', 20, 1);
        $loader->run();

        $this->assertGreaterThan(0, has_filter('the_content', 'my_function'));
    }

    /**
     * Test running the registered actions and filters.
     *
     * @return void
     */
    public function testRun() {
        $loader = new Loader();
        $loader->add_action('wp_head', 'my_function', 10, 2);
        $loader->add_filter('the_content', 'my_function', 20, 1);

        // Expect add_action and add_filter to be called twice
        Functions\expect('add_action')->twice();
        Functions\expect('add_filter')->twice();

        ob_start();
        $loader->run();
        ob_end_clean();

        $this->assertTrue(true); // Placeholder assertion to indicate the test ran successfully
    }
}
