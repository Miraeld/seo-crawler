<?php

namespace SEO_Crawler\Tests\Unit;

define( 'SEO_CRAWLER_PLUGIN_ROOT', dirname( dirname( __DIR__ ) ) . DIRECTORY_SEPARATOR );
define( 'SEO_CRAWLER_TESTS_FIXTURES_DIR', dirname( __DIR__ ) . '/Fixtures' );
define( 'SEO_CRAWLER_TESTS_DIR', __DIR__ );
define( 'SEO_CRAWLER_IS_TESTING', true );

// Set the path and URL to our virtual filesystem.
define( 'SEO_CRAWLER_CACHE_ROOT_PATH', 'vfs://public/wp-content/cache/' );
define( 'SEO_CRAWLER_CACHE_ROOT_URL', 'vfs://public/wp-content/cache/' );
define( 'OBJECT', 'OBJECT' );
/**
 * The original files need to loaded into memory before we mock them with Patchwork. Add files here before the unit
 * tests start.
 *
 * @since 3.5
 */
function load_original_files_before_mocking() {
	$originals = [
	];
	foreach ( $originals as $file ) {
		require_once SEO_CRAWLER_PLUGIN_ROOT . $file;
	}

	$fixtures = [
    ];
	foreach ( $fixtures as $file ) {
		require_once SEO_CRAWLER_TESTS_FIXTURES_DIR . $file;
	}
}

load_original_files_before_mocking();