<?php
/**
 * This file is responsible for handling the SEO Crawler administration functions.
 */

defined( 'ABSPATH' ) || exit;

use \SEO_Crawler\Utils\SeoCrawlerView;

/**
 * Hook into the admin_menu action to add SEO Crawler to the WordPress admin menu.
 */
add_action( 'admin_menu', 'seo_crawler_add_admin_menu' );

/**
 * Adds a new top-level menu page for the SEO Crawler.
 */
function seo_crawler_add_admin_menu() {
	// The title to be displayed in the browser title bar.
	// The text to be used for the menu.
	// The required capability of users to access this menu.
	// The slug by which this menu will be referred to.
	// The callback function used to render the settings page.
	// The icon to be used for this menu.
	// The position in the menu order this one should appear.
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
	SeoCrawlerView::render_view( 'admin/settings/index' );

	require_once 'seo-crawler-form-handler.php';
	seo_crawler_form_handler();
}

/**
 * Schedules the crawl event if it's not already scheduled.
 */
function seo_crawler_schedule_crawl_event() {
	if ( ! wp_next_scheduled( 'seo_crawler_crawl_event' ) ) {
		wp_schedule_event( time(), 'hourly', 'seo_crawler_crawl_event' );
	}
}

add_action( 'seo_crawler_crawl_event', 'seo_crawler_crawl_task' );

/**
 * Performs the crawling task.
 *
 * @param bool $render Whether to render the crawl results or not.
 */
function seo_crawler_crawl_task( $render = false ) {
	$crawler = new \SEO_Crawler\Crawl\SeoCrawlerCrawl();
	$crawler->executeCrawl();

	if ( $render ) {
		SeoCrawlerView::render_view( 'admin/crawl/results', [ 'results' => $crawler->getLatestResults() ] );
	}
}

/**
 * Displays the latest crawl results.
 */
function seo_crawler_display_latest_results() {
	$crawler = new \SEO_Crawler\Crawl\SeoCrawlerCrawl();
	SeoCrawlerView::render_view( 'admin/crawl/results', [ 'results' => $crawler->getLatestResults() ] );
}
