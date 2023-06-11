<?php
/**
 * Plugin Name: SEO Crawler
 * Description: WP Media Technical Test
 * Version: 1.0
 * Author: Gaël Robin
 *
 * This plugin allows to crawl the website to find and store its internal links.
 */

defined( 'ABSPATH' ) || exit;

// Include the init plugin file.
require_once plugin_dir_path( __FILE__ ) . 'inc/init.php';

/**
 * Handles the actions to be taken upon plugin activation.
 * Creates the required database table.
 *
 * This function is prefixed with the plugin name "seo_crawler" to avoid naming conflicts in the global namespace.
 */
function seo_crawler_activate() {
	$db = new \SEO_Crawler\Db\DbTable();
	$db->create_table();
}

register_activation_hook( __FILE__, 'seo_crawler_activate' );

/**
 * Handles the actions to be taken upon plugin deactivation.
 * Drops the database table created by the plugin.
 *
 * This function is prefixed with the plugin name "seo_crawler" to avoid naming conflicts in the global namespace.
 */
function seo_crawler_deactivate() {
	$db = new \SEO_Crawler\Db\DbTable();
	$db->drop_table();
}

register_deactivation_hook( __FILE__, 'seo_crawler_deactivate' );

// Include the admin file.
require_once plugin_dir_path( __FILE__ ) . 'admin/seo-crawler-admin.php';
