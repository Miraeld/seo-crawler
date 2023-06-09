<?php

/**
 * This file is responsible for handling the SEO Crawler administration functions.
 */

defined( 'ABSPATH' ) || exit;


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
	seo_crawler_render_view( 'admin/settings/index' );

}
