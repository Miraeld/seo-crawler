<?php

namespace SEO_Crawler\Tests\Unit\Utils;

use PHPUnit\Framework\TestCase;
use SEO_Crawler\Utils\Render;
use Brain\Monkey\Functions;

/**
 * Class RenderTest
 *
 * Test cases for the Render class.
 */
class RenderTest extends TestCase {
    /**
     * Set up the test environment.
     *
     * @return void
     */
    public function setUp(): void {
        Functions\when('plugin_dir_path')->alias(function ($file) {
            $test = dirname(__FILE__) . '/random';
            return $test;
        });
    }

    /**
     * Test rendering a view with no parameters.
     *
     * @return void
     */
    public function testRenderViewWithNoParams() {
        $this->expectException(\ArgumentCountError::class);
        $view = 'my_view';
        $expected_content = '<h1>This is my view</h1>';

        Render::render_view();


    }

    /**
     * Test rendering a view with parameters.
     *
     * @return void
     */
    public function testRenderViewWithParams() {
        $view = 'my_view';
        $expected_content = '<h1>This is my view</h1>';
    
        // Set up the view file
        $view_path = dirname(dirname( __DIR__ )) . "/inc/views/{$view}.php";
        $view_content = '<h1>This is my view</h1>';
        file_put_contents( $view_path, $view_content );
    
        ob_start();
        Render::render_view( $view, [ 'name' => 'World' ] );
        $rendered_content = ob_get_clean();
    
        $rendered_content = str_replace(';\Patchwork\CodeManipulation\Stream::reinstateWrapper();', '', $rendered_content);
    
        $this->assertEquals( $expected_content, $rendered_content );
    
        // Clean up the view file
        unlink( $view_path );
    }
    
    
}
