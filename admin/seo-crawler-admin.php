<?php
/**
 * This file is responsible for handling the SEO Crawler administration functions.
 */

defined( 'ABSPATH' ) || exit;

use \SEO_Crawler\Utils\Render;
use \SEO_Crawler\Utils\Loader;


$seo_crawler_loader = new Loader();
$seo_crawler_loader->add_action( 'admin_menu', 'seo_crawler_add_admin_menu' );
$seo_crawler_loader->add_action( 'seo_crawler_crawl_event', 'seo_crawler_crawl_task' );


/**
 * Adds a new top-level menu page for the SEO Crawler.
 */
function seo_crawler_add_admin_menu() {
	add_menu_page(
		'SEO Crawler Settings',
		'SEO Crawler',
		'manage_options',
		'seo-crawler',
		'seo_crawler_settings_page',
		'dashicons-search',
		100
	);
}

/**
 * Renders the SEO Crawler settings page and handles the crawl action.
 */
function seo_crawler_settings_page() {
	// Check user capabilities.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Render the settings page.
	Render::render_view( 'admin/settings/index' );

	require_once 'seo-crawler-form-handler.php';
	seo_crawler_form_handler();
}

/**
 * Schedules the crawl event if it's not already scheduled.
 */
function seo_crawler_schedule_crawl_event() {
	if ( wp_next_scheduled( 'seo_crawler_crawl_event' ) ) {
		wp_clear_scheduled_hook( 'seo_crawler_crawl_event' );
	}
	wp_schedule_event( time(), 'hourly', 'seo_crawler_crawl_event' );
	( new SEO_Crawler\Utils\Notice( 'success', 'Next crawl schedule in a hour!' ) )->render();
}


/**
 * Performs the crawling task.
 *
 * @param bool $render Whether to render the crawl results or not.
 */
function seo_crawler_crawl_task( $render = false ) {
	$crawler = new \SEO_Crawler\Crawl\Crawler();
	$crawler->executeCrawl();

	if ( $render ) {
		Render::render_view( 'admin/crawl/results', [ 'results' => $crawler->getLatestResults() ] );
	}
}

/**
 * Displays the latest crawl results.
 */
function seo_crawler_display_latest_results() {
	$crawler = new \SEO_Crawler\Crawl\Crawler();
	Render::render_view( 'admin/crawl/results', [ 'results' => $crawler->getLatestResults() ] );
}
$seo_crawler_loader->run();
